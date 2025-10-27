<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\UserCoupon;
use App\Models\Product;
use App\Models\Variation;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;
use App\Models\Setting;
use App\Models\UserAddress;
use App\Exports\InvoiceExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function buyAgain()
    {
        // Get authenticated user
        $user = Auth::guard('user-api')->user();

        // Find the latest order for this user
        $latestOrder = Order::where('user_id', $user->id)
            ->with([
                'orderProducts.product.category',
                'orderProducts.product.variations',
                'orderProducts.product.productImages',
                'orderProducts.product.units',
                'orderProducts.product.unit',
                'orderProducts.variation',
                'orderProducts.unit'
            ])
            ->first();

        if (!$latestOrder) {
            return response()->json([
                'status' => false,
                'message' => 'No previous orders found'
            ], 404);
        }

        // Extract products from the latest order
        $itemlist = collect();
        foreach ($latestOrder->orderProducts as $orderProduct) {
            $product = $orderProduct->product;

            if ($product && $product->status == 1) {
                // Determine user type and set properties accordingly
                $userType = $user->user_type;

                if ($userType == 1) {
                    $product->unit_name = $product->unit ? $product->unit->name_ar : null;
                    $product->price = $product->selling_price_for_user;
                    $product->quantity = $product->available_quantity_for_user;
                } elseif ($userType == 2) {
                    $unit = $product->units->first();
                    $product->unit_name = $unit ? $unit->name_ar : null;
                    $product->price = $unit ? $unit->pivot->selling_price : null;
                    $product->quantity = $product->available_quantity_for_wholeSale;
                }

                // Set additional product properties
                $product->is_favourite = $user->favourites()->where('product_id', $product->id)->exists();
                $product->rating = $product->rating;
                $product->total_rating = $product->total_rating;
                $product->has_offer = $product->offers()->exists();
                $product->offer_id = $product->has_offer ? $product->offers()->first()->id : 0;
                $product->offer_price = $product->has_offer ? $product->offers()->first()->price : 0;

                $itemlist->push($product);
            }
        }

        return response()->json(['data' => $itemlist]);
    }

    public function index()
    {
        $user_id = auth()->user()->id;

        $orders = Order::with([
            'orderProducts' => function ($query) {
                $query->with([
                    'product' => function ($query) {
                        $query->with(['category', 'productImages']);
                    },
                    'unit' => function ($query) {
                        $query->select('id', 'name_en', 'name_ar'); // Include locale fields
                    },
                    'variation' => function ($query) {
                        $query->select('id', 'product_id', 'variation');
                    }
                ]);
            },
            'address',
            'user',

        ])->where('user_id', $user_id)->get();

        return response()->json(['data' => $orders]);
    }


    public function show($id)
    {
        $user_id = auth()->user()->id;

        $order = Order::with([
            'orderProducts' => function ($query) {
                $query->with([
                    'product' => function ($query) {
                        $query->with(['category', 'productImages']);
                    },
                    'unit' => function ($query) {
                        $query->select('id', 'name_en', 'name_ar');
                    },
                    'variation' => function ($query) {
                        $query->select('id', 'product_id', 'variation');
                    }
                ]);
            },
            'address',
            'user',
        ])
            ->where('user_id', $user_id)
            ->where('id', $id)
            ->first();

        if (!$order) {
            return response()->json([
                'message' => 'Order not found or you do not have permission to view this order'
            ], 404);
        }


        return response()->json([
            'data' => $order,

        ]);
    }



    public function store(Request $request)
    {
        // Get authenticated user's ID and user type
        $user_id = auth()->user()->id;
        $user_type = auth()->user()->user_type;

        // Validate the request data
        $request->validate([
            'payment_type' => 'required',
            'address_id'     => 'required_unless:type_delivery,1|nullable|exists:user_addresses,id',
            'note' => 'nullable',
            'date' => 'nullable',
            'from_time' => 'nullable',
            'to_time' => 'nullable',
            'photo_note' => 'nullable',
            'phone_in_order' => 'required',
            'type_delivery' => 'required',
        ]);

        // Find all the cart items with status 1 for the current user
        $cartItems = Cart::where('user_id', $user_id)->where('status', 1)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'No items in the cart.'], 400);
        }

        // Start a transaction to ensure atomicity
        DB::beginTransaction();

        try {
            // Initialize calculation variables
            $total_discounts = 0;
            $total_taxes = 0;
            $total_prices = 0;

            // Get address and delivery fee
            $delivery_fee = 0;

            if ($request->type_delivery != 1) {
                $address = UserAddress::with('delivery')->findOrFail($request->input('address_id'));
                $delivery_fee = $address->delivery->price ?? 0;
            }

            // Calculate totals from cart items - USE EXACT CART VALUES
            foreach ($cartItems as $cartItem) {
                $total_discounts += $cartItem->discount_coupon ?? 0;
                $total_taxes += $cartItem->product->tax ?? 0;
                $total_prices += $cartItem->total_price_product;
            }

            // Check for minimum order only if settings are active
            $activeSetting = Setting::where('status', 1)->first();

            // Only check minimum order if there's an active setting
            if ($activeSetting) {
                // Check minimum order based on user type
                if ($user_type == 1) {
                    // Regular user - check min_order
                    if ($total_prices < $activeSetting->min_order) {
                        DB::rollBack();
                        Log::info('Minimum Order Check Failed - Retail User', [
                            'user_id' => $user_id,
                            'current_total' => $total_prices,
                            'minimum_required' => $activeSetting->min_order
                        ]);
                        return response()->json([
                            'message' => 'Your order is less than the minimum order for retail customers. Please buy at least ' . $activeSetting->min_order,
                            'minimum_required' => $activeSetting->min_order,
                            'current_total' => $total_prices,
                            'user_type' => 'retail'
                        ], 400);
                    }
                } else {
                    // Wholesale user (user_type = 2) - check min_order_wholeSale
                    if ($total_prices < $activeSetting->min_order_wholeSale) {
                        DB::rollBack();
                        Log::info('Minimum Order Check Failed - Wholesale User', [
                            'user_id' => $user_id,
                            'current_total' => $total_prices,
                            'minimum_required' => $activeSetting->min_order_wholeSale
                        ]);
                        return response()->json([
                            'message' => 'Your order is less than the minimum order for wholesale customers. Please buy at least ' . $activeSetting->min_order_wholeSale,
                            'minimum_required' => $activeSetting->min_order_wholeSale,
                            'current_total' => $total_prices,
                            'user_type' => 'wholesale'
                        ], 400);
                    }
                }
            } else {
                // No active settings found - skip minimum order check
                Log::info('Minimum order check skipped - no active settings', ['user_id' => $user_id]);
            }

            // Generate the order number based on order type, ensuring uniqueness
            $lastOrder = Order::where('order_type', 1)->orderBy('number', 'desc')->lockForUpdate()->first();
            $newNumber = $lastOrder ? $lastOrder->number + 1 : 1;

            $lastOrderForRefund = Order::where('order_type', 2)->orderBy('number', 'desc')->lockForUpdate()->first();
            $newNumberOfRefund = $lastOrderForRefund ? $lastOrderForRefund->number + 1 : 1;

            $orderNumber = $request->order_type == 2 ? $newNumberOfRefund : $newNumber;

            // Create a new order
            $order = new Order([
                'number' => $orderNumber,
                'address_id' => $request->input('address_id'),
                'payment_type' => $request->input('payment_type'),
                'note' => $request->input('note'),
                'total_discounts' => $total_discounts,
                'coupon_discount' => $total_discounts, // Store the coupon discount
                'delivery_fee' => $delivery_fee,
                'total_taxes' => $total_taxes,
                'total_prices' => $total_prices + $delivery_fee,
                'phone_in_order' => $request->phone_in_order,
                'type_delivery' => $request->type_delivery,
                'date' => $request->date,
                'from_time' => $request->from_time,
                'to_time' => $request->to_time,
                'user_id' => $user_id,
            ]);

            // Handle photo note upload
            if ($request->hasFile('photo_note')) {
                $the_file_path = uploadImage('assets/admin/uploads', $request->file('photo_note'));
                $order->photo_note = $the_file_path;
            }
            $order->save();

            // Attach cart items to the order - PRESERVE EXACT CART CALCULATIONS
            foreach ($cartItems as $cartItem) {
                $unit_id = ($user_type == 1) ? $cartItem->product->unit->id : $cartItem->product->units()->first()->id;

                // Use the exact values from cart instead of recalculating
                $total_price_after_tax = $cartItem->total_price_product;
                $discount_amount = $cartItem->discount_coupon ?? 0;

                // Calculate price before tax and discount
                $tax_rate = $cartItem->product->tax / 100;
                $total_price_before_tax = $total_price_after_tax / (1 + $tax_rate);

                // Calculate discount percentage based on actual discount amount
                $discount_percentage = $total_price_before_tax > 0 ? ($discount_amount / $total_price_before_tax) : 0;

                // Calculate tax value on the discounted amount
                $price_after_discount = $total_price_before_tax - $discount_amount;
                $tax_value = $price_after_discount * $tax_rate;

                $order->products()->attach($cartItem->product_id, [
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->price,
                    'variation_id' => $cartItem->variation_id,
                    'unit_id' => $unit_id,
                    'total_price_after_tax' => $total_price_after_tax,
                    'total_price_before_tax' => $total_price_before_tax,
                    'tax_percentage' => $cartItem->product->tax ?? 0,
                    'discount_percentage' => $discount_percentage,
                    'discount_value' => $discount_amount, // Use exact discount from cart
                    'line_discount_percentage' => null,
                    'line_discount_value' => null,
                    'tax_value' => $tax_value,
                ]);
            }

            Cart::where('user_id', $user_id)->where('status', 1)->update([
                'status' => 2,
            ]);

            // Mark applied coupons as used by creating UserCoupon records
            $appliedCouponIds = $cartItems->whereNotNull('applied_coupon_id')
                ->pluck('applied_coupon_id')
                ->unique();

            foreach ($appliedCouponIds as $couponId) {
                // Check if not already exists to avoid duplicates
                if (!UserCoupon::where('user_id', $user_id)->where('coupon_id', $couponId)->exists()) {
                    UserCoupon::create([
                        'user_id' => $user_id,
                        'coupon_id' => $couponId,
                    ]);
                }
            }




            // Commit the transaction
            DB::commit();

            // Log the response and return success
            Log::info('Order Created Successfully', [
                'order_id' => $order->id,
                'user_id' => $user_id,
                'user_type' => $user_type,
                'total_prices' => $total_prices,
                'total_discounts' => $total_discounts
            ]);

            return response()->json($order, 200);
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();
            Log::error('Order Creation Error', [
                'error' => $e->getMessage(),
                'user_id' => $user_id,
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return response()->json(['message' => 'Order creation failed', 'error' => $e->getMessage()], 500);
        }
    }



    public function cancel_order($id)
    {
        // Find the order by ID
        $order = Order::find($id);

        // Check if the order exists
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Check if the order is cancellable (you may need to add additional logic here)
        if ($order->order_status == 1) {
            // Update the order status to cancelled
            $order->update(['order_status' => 5]);


            return response()->json(['message' => 'Order cancelled successfully'], 200);
        } else {
            return response()->json(['message' => 'Order is already cancelled'], 422);
        }
    }
}
