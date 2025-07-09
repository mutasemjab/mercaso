<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Models\NoteVoucher;
use App\Models\VoucherProduct;
use App\Models\OrderProduct;
use Carbon\Carbon;

class ProductReportController extends Controller
{
   
    // public function index(Request $request) 
    // {
    //     $shops = Shop::all();
    //     $products = Product::all();
    //     $shopId = $request->input('shop_id');
    //     $productId = $request->input('product_id');
    //     $fromDate = $request->input('from_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
    //     $toDate = $request->input('to_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

    //     $reportData = [];
    //     $totalIn = 0;  // To track all "in" quantities
    //     $totalOut = 0; // To track all "out" quantities

    //     if ($shopId && $productId) {
    //         // Fetch note vouchers with voucherProducts and their products' units
    //         $noteVouchers = NoteVoucher::with(['voucherProducts' => function ($query) use ($productId) {
    //             $query->where('product_id', $productId)->with('units');
    //         }])
    //         ->where('shop_id', $shopId)
    //         ->whereBetween('date_note_voucher', [$fromDate, $toDate])
    //         ->get();

    //         // Fetch only the orders that contain the specified product_id
    //         $orders = Order::where('shop_id', $shopId)
    //             ->where('order_status', 4) // Delivered orders
    //             ->whereBetween('date', [$fromDate, $toDate])
    //             ->whereHas('orderProducts', function ($query) use ($productId) {
    //                 $query->where('product_id', $productId);
    //             })
    //             ->with(['orderProducts' => function ($query) use ($productId) {
    //                 $query->where('product_id', $productId);
    //             }])
    //             ->get();

    //         // Process the report data
    //         foreach ($noteVouchers as $voucher) {
    //             foreach ($voucher->voucherProducts as $voucherProduct) {
    //                 $product = $voucherProduct;
    //                 $quantity = $voucherProduct->pivot->quantity;
    //                 $unitName = null;

    //                 if ($product) {
    //                     // Fetch the unit from the units table based on unit_id in the pivot
    //                     $unit = \App\Models\Unit::find($voucherProduct->pivot->unit_id);

    //                     if ($unit) {
    //                         $unitName = $unit->name;

    //                         // Check if there is an alternative unit in product_units
    //                         $productUnit = $product->units->where('id', $voucherProduct->pivot->unit_id)->first();
    //                         if ($productUnit) {
    //                             // Adjust the quantity based on the 'releation' from product_units
    //                             $quantity *= $productUnit->pivot->releation;
    //                             $unitName = $productUnit->name;
    //                         }
    //                     }
    //                 }

    //                 // Append to report data
    //                 $reportData['noteVouchers'][] = [
    //                     'voucher_id' => $voucher->id,
    //                     'note' => $voucher->note,
    //                     'date_note_voucher' => $voucher->date_note_voucher,
    //                     'from_warehouse' => $voucher->fromWarehouse->name,
    //                     'note_voucher_type' => $voucher->note_voucher_type_id,
    //                     'unit_name' => $unitName,
    //                     'quantity' => $quantity,
    //                     'purchasing_price' => $voucherProduct->pivot->purchasing_price,
    //                 ];

    //                 // Calculate totals for "in" and "out"
    //                 if (in_array($voucher->note_voucher_type_id, [1, 3])) {
    //                     $totalIn += $quantity; // "In" case
    //                 } elseif ($voucher->note_voucher_type_id == 2) {
    //                     $totalOut += $quantity; // "Out" case
    //                 }
    //             }
    //         }

    //         // Add the order data similarly
    //         foreach ($orders as $order) {
    //             $reportData['orders'][] = [
    //                 'order_id' => $order->id,
    //                 'order_number' => $order->number,
    //                 'user_name' => $order->user->name ?? 'N/A',
    //                 'date' => $order->date,
    //                 'total_prices' => $order->total_prices,
    //                 'order_products' => $order->orderProducts->map(function($orderProduct) {
    //                     return [
    //                         'product_name' => $orderProduct->product->name,
    //                         'quantity' => $orderProduct->quantity,
    //                         'unit_price' => $orderProduct->unit_price,
    //                         'total_price_after_tax' => $orderProduct->total_price_after_tax,
    //                     ];
    //                 })
    //             ];
    //         }
    //     }

    //     // Calculate the net quantity: Total "in" minus total "out"
    //     $netQuantity = $totalIn - $totalOut;

    //     return view('reports.product_move', compact('shops', 'products', 'reportData', 'shopId', 'fromDate', 'toDate', 'productId', 'totalIn', 'totalOut', 'netQuantity'));
    // }


    /// FOR Display all produtcs
    public function allProducts(Request $request)
    {
        $toDate = $request->input('to_date', date('Y-m-d'));

        $reportData = [];
        $products = collect(); // Initialize $products as an empty collection

            // Get product details
            $products = Product::with('unit', 'units', 'category')
                            ->whereDate('created_at', '<=', $toDate)
                            ->get();

            $reportData = [
                'products' => $products,
            ];
        

        return view('reports.products', compact( 'products', 'reportData', 'toDate'));
    }

}
