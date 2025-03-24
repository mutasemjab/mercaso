<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NoteVoucher;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {


        // Create an initial query to retrieve orders for the authenticated user's 
        $query = Order::query();

        // Apply search filter if present in the request
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('number', 'LIKE', "%$search%");
            });
        }

        // Retrieve all orders with pagination
        $data = $query->orderBy('created_at', 'desc')->paginate(PAGINATION_COUNT);

        return view('admin.orders.index', compact('data'));
    }


    public function create()
    {
        $products = Product::get();
        $users = User::get();
        $warehouses = Warehouse::get();
        return view('admin.orders.create', compact('products','users','warehouses'));
    }



public function store(Request $request)
{

    // Validate the request data
    $validatedData = $request->validate([
        'order_type' => 'required|integer',
        'date' => 'required|date',
        'payment_type' => 'required|string',
        'address' => 'required|integer|exists:user_addresses,id',
        'products' => 'required|array',
        'products.*.name' => 'required|string',
        'products.*.unit' => 'required|integer|exists:units,id',
        'products.*.quantity' => 'required|integer|min:1',
        'products.*.selling_price_without_tax' => 'required|numeric',
        'products.*.selling_price_with_tax' => 'required|numeric',
        'products.*.tax' => 'required|numeric',
        'products.*.discount_fixed' => 'nullable|numeric',
        'products.*.discount_percentage' => 'nullable|numeric',
        'coupon_discount' => 'nullable|numeric|min:0|max:100'
    ]);

    // Start a transaction to prevent duplicates
    DB::beginTransaction();

    try {
        // Lock the order table for update to avoid race conditions
       $lastOrder = Order::where('order_type', $request->order_type)
                  ->orderByRaw('CAST(number AS UNSIGNED) DESC')
                  ->lockForUpdate()
                  ->first();


        // Generate the unique order number
        $newOrderNumber = $lastOrder ? $lastOrder->number + 1 : 1;
      //  return $newOrderNumber;
        // Check for existing order with the same number and order type
        $existingOrder = Order::where('order_type', $request->order_type)
                              ->where('number', $newOrderNumber)
                              ->first();

        if ($existingOrder) {
            DB::rollBack();
            return response()->json(['message' => 'Duplicate order detected.'], 409);
        }

        // Define the order status
        $orderStatus = $request->order_type == 2 ? 6 : 1;
        $address = UserAddress::find($request->address);
        $deliveryFee = doubleval($address->delivery->price ?? 0);

        $user = User::where('name', $request->user)->first();

        // Create the order
        $order = Order::create([
            'number' => $newOrderNumber,
            'order_status' => $orderStatus,
            'total_taxes' => 0,
            'delivery_fee' => $deliveryFee,
            'total_prices' => 0,
            'total_discounts' => 0,
            'payment_type' => $request->payment_type,
            'payment_status' => 2,
            'order_type' => $request->order_type,
            'date' => Carbon::parse($request->date),
            'user_id' => $user->id,
            'address_id' => $request->address,
            'coupon_discount' => $request->coupon_discount ?? 0,
        ]);

        // Initialize totals
        $totalTaxes = 0;
        $totalPrices = 0;
        $totalDiscounts = 0;
        $couponDiscountPercentage = $request->coupon_discount ?? 0;

        // Process each product in the order
        foreach ($request->products as $productData) {
            $product = Product::where('name_ar', $productData['name'])->first();

            if ($product) {
                $quantity = $productData['quantity'];
                $unitPriceWithoutTax = $productData['selling_price_without_tax'];
                $taxPercentage = $productData['tax'];
                $unitPriceWithTax = $unitPriceWithoutTax * (1 + $taxPercentage / 100);

                $totalPriceBeforeTax = $unitPriceWithoutTax * $quantity;
                $totalPriceAfterTax = $unitPriceWithTax * $quantity;

                $lineDiscountFixed = $productData['discount_fixed'] ?? 0;
                $lineDiscountPercentage = $productData['discount_percentage'] ?? 0;
                $lineDiscountValue = ($totalPriceBeforeTax * $lineDiscountPercentage / 100) + $lineDiscountFixed;
                $totalPriceAfterLineDiscount = $totalPriceBeforeTax - $lineDiscountValue;

                $couponDiscountValue = $totalPriceAfterLineDiscount * ($couponDiscountPercentage / 100);
                $totalPriceAfterAllDiscounts = $totalPriceAfterLineDiscount - $couponDiscountValue;

                $totalRowTax = $totalPriceAfterAllDiscounts * ($taxPercentage / 100);

                OrderProduct::create([
                    'unit_id' => $productData['unit'],
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'variation_id' => null,
                    'quantity' => $quantity,
                    'unit_price' => $unitPriceWithTax,
                    'total_price_after_tax' => $totalPriceAfterAllDiscounts + $totalRowTax,
                    'tax_percentage' => $taxPercentage,
                    'tax_value' => $totalRowTax,
                    'total_price_before_tax' => $totalPriceBeforeTax,
                    'line_discount_percentage' => $lineDiscountPercentage,
                    'line_discount_value' => $lineDiscountValue,
                    'discount_value' => $couponDiscountValue,
                ]);

                // Accumulate totals
                $totalTaxes += $totalRowTax;
                $totalPrices += $totalPriceAfterAllDiscounts + $totalRowTax;
                $totalDiscounts += $lineDiscountValue + $couponDiscountValue;
            }
        }

        // Update the order with the calculated totals
        $order->update([
            'total_taxes' => $totalTaxes,
            'total_prices' => $totalPrices + $deliveryFee,
            'total_discounts' => $totalDiscounts,
        ]);

        // Handle refund logic
        if ($order->order_type == 2) {
            $lastNoteVoucher = NoteVoucher::orderBy('id', 'desc')->first();
            $newVoucherNumber = $lastNoteVoucher ? $lastNoteVoucher->id + 1 : 1;

            // Create the note voucher
            $noteVoucher = NoteVoucher::create([
                'note_voucher_type_id' => 1,
                'date_note_voucher' => $order->date,
                'number' => $newVoucherNumber,
                'from_warehouse_id' => $order->warehouse->id ?? 1,
                'to_warehouse_id' => $request['toWarehouse'] ?? null,
                'order_id' => $order->id,
                'note' => "فاتورة مرتجع رقم " . (string)$order->number,
            ]);

            // Attach products to the voucher
            foreach ($request['products'] as $productData) {
                $product = Product::where('name_ar', $productData['name'])->firstOrFail();

                $noteVoucher->voucherProducts()->attach($product->id, [
                    'unit_id' => $productData['unit'],
                    'quantity' => $productData['quantity'],
                    'purchasing_price' => $productData['purchasing_price'] ?? null,
                    'note' => $productData['note'] ?? null,
                ]);
            }
        }

        // Commit the transaction
        DB::commit();

        // Redirect to the appropriate page
        if ($request->redirect_to == 'index') {
            return redirect()->route('orders.index')->with('success', 'Order created successfully.');
        } else {
            return redirect()->route('orders.show', $order->id)->with('success', 'Order created successfully.');
        }

    } catch (\Exception $e) {
        // Rollback in case of an error
        DB::rollBack();
        return response()->json(['message' => 'Order creation failed', 'error' => $e->getMessage()], 500);
    }
}



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::with('products', 'products.variations')->findOrFail($id);
        return view('admin.orders.show', compact('order'));

    }

    public function edit($id)
    {
        $order = Order::with(['products.units', 'products.unit', 'user.addresses'])->findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

 public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'date' => 'required|date',
        'payment_type' => 'required|string',
        'products' => 'required|array',
        'products.*.name' => 'required|string',
        'products.*.unit' => 'required|integer|exists:units,id',
        'products.*.quantity' => 'required|integer|min:1',
        'products.*.selling_price_without_tax' => 'required|numeric',
        'products.*.selling_price_with_tax' => 'required|numeric',
        'products.*.tax' => 'required|numeric',
        'products.*.line_discount_fixed' => 'nullable|numeric',
        'products.*.line_discount_percentage' => 'nullable|numeric',
        'coupon_discount' => 'nullable|numeric|min:0|max:100'
    ]);

    $order = Order::findOrFail($id);
    $order->update([
        'order_status' => $request->order_status,
        'date' => Carbon::parse($request->date),

        'payment_type' => $request->payment_type,
        'address_id' => $request->address,
        'user_id' => User::where('name', $request->user)->first()->id,
        'coupon_discount' => $request->coupon_discount,
    ]);

    // Reset totals
    $totalTaxes = 0;
    $totalPrices = 0;
    $totalDiscounts = 0;
    $totalBeforeTax = 0;
    $couponDiscountPercentage = $request->coupon_discount ?? 0;

    // Detach old products
    $order->products()->detach();

    // Attach new products
    foreach ($request->products as $productData) {
        $product = Product::where('name_ar', $productData['name'])->first();

        if ($product) {
            $quantity = $productData['quantity'];
            $unitPriceWithoutTax = $productData['selling_price_without_tax'];
            $taxPercentage = $productData['tax'];
            $unitPriceWithTax = $unitPriceWithoutTax * (1 + $taxPercentage / 100);

            $totalPriceBeforeTax = $unitPriceWithoutTax * $quantity;

            // Get the original discount values from the form submission
            $originalLineDiscountValue = $productData['original_line_discount_value'] ?? 0;
            $originalLineDiscountPercentage = $productData['original_line_discount_percentage'] ?? 0;

            // Get new discount inputs from the form
            $lineDiscountPercentage = $productData['line_discount_percentage'] ?? 0;
            $lineDiscountValue = $productData['line_discount_value'] ?? 0;

            // Recalculate the line discount value if the percentage has changed
            if ($lineDiscountPercentage != $originalLineDiscountPercentage) {
                $lineDiscountValue = ($totalPriceBeforeTax * $lineDiscountPercentage / 100);
            }

            // If the line_discount_value was manually changed and percentage remains unchanged, keep it as is
            if ($lineDiscountValue != $originalLineDiscountValue && $lineDiscountPercentage == $originalLineDiscountPercentage) {
                // No recalculation required for the manually changed line_discount_value
            }

            // Calculate totals after applying the discount
            $totalPriceAfterLineDiscount = $totalPriceBeforeTax - $lineDiscountValue;
            $totalRowTax = $totalPriceAfterLineDiscount * ($taxPercentage / 100);
            $totalPriceAfterTax = $totalPriceAfterLineDiscount + $totalRowTax;

            // Attach the product to the order with the correct discount and tax values
            $order->products()->attach($product->id, [
                'unit_id' => $productData['unit'],
                'variation_id' => null,
                'quantity' => $quantity,
                'unit_price' => $unitPriceWithTax,
                'total_price_after_tax' => $totalPriceAfterTax,  // Corrected line
                'tax_percentage' => $taxPercentage,  // Corrected line
                'tax_value' => $totalRowTax,  // Corrected line
                'total_price_before_tax' => $totalPriceBeforeTax,
                'line_discount_percentage' => $lineDiscountPercentage,  // Save the updated percentage
                'line_discount_value' => $lineDiscountValue,  // Save the updated or manually entered discount value
            ]);

            // Accumulate totals
            $totalTaxes += $totalRowTax;
            $totalPrices += $totalPriceAfterTax;
            $totalDiscounts += $lineDiscountValue;
        }
    }





    // Update order totals
    $order->update([
        'total_taxes' => $totalTaxes,
        'total_prices' => $totalPrices + $order->delivery_fee,
        'total_discounts' => $totalDiscounts,
    ]);

     if ($order->order_type == 1 && $order->order_status == 4) {
         $lastNoteVoucher = NoteVoucher::orderBy('id', 'desc')->first();
         $newNumber = $lastNoteVoucher ? $lastNoteVoucher->id + 1 : 1;

         // Create the note voucher
         $noteVoucher = NoteVoucher::create([
             'note_voucher_type_id' => 2,
             'date_note_voucher' => $order->date,
             'number' => $newNumber,
             'from_warehouse_id' => $order->warehouse->id ?? 1,
             'to_warehouse_id' => $request['toWarehouse'] ?? null,

             'order_id' =>  $order->id,
             'note' => "فاتورة بيع رقم " . (string)$order->number,
         ]);

         // Save the products and update quantities
         foreach ($request['products'] as $productData) {
            $product = Product::where('name_ar', $productData['name'])->firstOrFail();

             // Attach product to voucher
             $noteVoucher->voucherProducts()->attach($product->id, [
                 'unit_id' => $productData['unit'],
                 'quantity' => $productData['quantity'],
                 'purchasing_price' => $productData['purchasing_price'] ?? null,
                 'note' => $productData['note'] ?? null,
             ]);
         }

     } elseif ($order->order_type == 2) {
         // كمرتجع
         $lastNoteVoucher = NoteVoucher::orderBy('id', 'desc')->first();
         $newNumber = $lastNoteVoucher ? $lastNoteVoucher->id + 1 : 1;

         // Create the note voucher
         $noteVoucher = NoteVoucher::create([
             'note_voucher_type_id' => 1,
             'date_note_voucher' => $order->date,
             'number' => $newNumber,
             'from_warehouse_id' => $order->warehouse->id ?? 1,
             'to_warehouse_id' => $request['toWarehouse'] ?? null,

             'order_id' =>  $order->id,
             'note' => "فاتورة مرتجع رقم " . (string)$order->number,
         ]);

         // Save the products and update quantities
         foreach ($request['products'] as $productData) {
             $product = Product::where('name_ar', $productData['name'])->firstOrFail();

             // Attach product to voucher
             $noteVoucher->voucherProducts()->attach($product->id, [
                 'unit_id' => $productData['unit'],
                 'quantity' => $productData['quantity'],
                 'purchasing_price' => $productData['purchasing_price'] ?? null,
                 'note' => $productData['note'] ?? null,
             ]);
         }
     }

    return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
}



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // Find the order by ID
            $order = Order::findOrFail($id);

            // Detach all related products from the order
            $order->products()->detach();

            // Find the related NoteVoucher
            $noteVoucher = NoteVoucher::where('order_id', $order->id)->first();

            if ($noteVoucher) {
                // Detach all related VoucherProducts from the NoteVoucher
                $noteVoucher->voucherProducts()->detach();

                // Delete the NoteVoucher
                $noteVoucher->delete();
            }

            // Delete the order
            $order->delete();

            // Redirect with success message
            return redirect()->route('orders.index')->with('success', 'Order and associated NoteVoucher deleted successfully.');
        } catch (\Exception $e) {
            // Redirect with error message
            return redirect()->route('orders.index')->with('error', 'Error deleting order: ' . $e->getMessage());
        }
    }


}
