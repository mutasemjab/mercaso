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
use App\Models\ProductUnit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AllProductReportController extends Controller
{

    public function index(Request $request)
    {
        $shops = Shop::all();
        $products = Product::all();
        $shopId = $request->input('shop_id');
        $fromDate = $request->input('from_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $reportData = [];

        if ($shopId) {
            foreach ($products as $product) {
                $productId = $product->id;

                // Get Beginning Balance & Purchase Price
                [$beginningBalance, $purchasePrice] = $this->calculateBeginningBalance($shopId, $productId, $fromDate);

                // Get In & Out Quantities
                $inQuantity = $this->getProductInQuantity($shopId, $productId, $fromDate, $toDate);
                [$outWholesale, $outNormal, $wholesaleUnitPrice, $normalUnitPrice, $wholesaleTotal, $normalTotal] =
                    $this->getProductOutQuantity($shopId, $productId, $fromDate, $toDate);

                // **Convert Wholesale Out to Normal User Unit**
                $releation = \App\Models\ProductUnit::where('product_id', $productId)->value('releation') ?? 1;
                $outWholesaleConverted = $outWholesale * $releation;

                // **Calculate New Single `remaining` Column**
                $remaining = ($beginningBalance + $inQuantity) - ($outWholesaleConverted + $outNormal);

                // Store Report Data
                $reportData[] = [
                    'product_name' => $product->name,
                    'beginning_balance' => $beginningBalance,
                    'purchase_price' => $purchasePrice,
                    'in_quantity' => $inQuantity,
                    'out_wholesale' => $outWholesale,
                    'wholesale_unit_price' => $wholesaleUnitPrice,
                    'wholesale_total' => $wholesaleTotal,
                    'out_normal' => $outNormal,
                    'normal_unit_price' => $normalUnitPrice,
                    'normal_total' => $normalTotal,
                    'remaining' => $remaining, // **New Column**
                ];
            }
        }

        return view('reports.all_products', compact('shops', 'products', 'reportData', 'shopId', 'fromDate', 'toDate'));
    }





    private function calculateBeginningBalance($shopId, $productId, $fromDate)
    {
        $beginningBalance = 0;
        $purchasePrice = 0;

        $noteVouchers = NoteVoucher::where('shop_id', $shopId)
            ->where('date_note_voucher', '<', $fromDate)
            ->with(['voucherProducts'])
            ->get();

        foreach ($noteVouchers as $noteVoucher) {
            foreach ($noteVoucher->voucherProducts as $voucherProduct) {
                if ($voucherProduct->id == $productId) {
                    $quantity = $voucherProduct->pivot->quantity;
                    $price = $voucherProduct->pivot->purchasing_price;
                    $type = $noteVoucher->note_voucher_type_id;

                    if ($type == 1 || $type == 3) { // In (New Purchase)
                        $beginningBalance += $quantity;
                        $purchasePrice = $price; // Store last purchase price
                    } elseif ($type == 2) { // Out
                        $beginningBalance -= $quantity;
                    }
                }
            }
        }

        return [$beginningBalance, $purchasePrice];
    }

    private function getProductInQuantity($shopId, $productId, $fromDate, $toDate)
    {
        $noteVouchers = NoteVoucher::where('shop_id', $shopId)
            ->whereBetween('date_note_voucher', [$fromDate, $toDate])
            ->with(['voucherProducts' => function ($query) use ($productId) {
                $query->where('product_id', $productId);
            }])
            ->get();

        $totalIn = 0;

        foreach ($noteVouchers as $voucher) {
            foreach ($voucher->voucherProducts as $voucherProduct) {
                // Convert quantity if needed (Unit Conversion)
                $quantity = $voucherProduct->pivot->quantity;

                $product = Product::find($productId);
                if ($voucherProduct->pivot->unit_id != $product->unit_id) {
                    $productUnit = $product->units()->where('unit_id', $voucherProduct->pivot->unit_id)->first();
                    if ($productUnit) {
                        $quantity *= $productUnit->pivot->releation;
                    }
                }

                // Only count 'in' transactions (type 1 and 3)
                if (in_array($voucher->note_voucher_type_id, [1, 3])) {
                    $totalIn += $quantity;
                }
            }
        }

        return $totalIn;
    }




    private function getProductOutQuantity($shopId, $productId, $fromDate, $toDate)
    {
            $orders = Order::where('shop_id', $shopId)
                ->where('order_status', 4) // Only delivered orders
                ->whereBetween('date', [$fromDate, $toDate])
                ->whereHas('orderProducts', function ($query) use ($productId) {
                    $query->where('product_id', $productId);
                })
                ->with('orderProducts')
                ->get();

            $outWholesale = 0;
            $outNormal = 0;
            $wholesaleUnitPrice = 0;
            $normalUnitPrice = 0;
            $wholesaleTotal = 0;
            $normalTotal = 0;

            foreach ($orders as $order) {
                foreach ($order->orderProducts as $orderProduct) {
                if ($orderProduct->product_id == $productId) {
                    $quantity = $orderProduct->quantity;
                    $unitPriceWithoutTax = $orderProduct->unit_price;
                    $totalPriceBeforeTax = $orderProduct->total_price_before_tax ?? ($unitPriceWithoutTax * $quantity);
                    $discount = $orderProduct->line_discount_value ?? 0;
                    $totalPriceAfterDiscount = $totalPriceBeforeTax - $discount;
                    $unitId = $orderProduct->unit_id;

                    // **Get the Normal User Unit ID from the Products Table**
                    $product = \App\Models\Product::find($productId);
                    $normalUserUnitId = $product->unit_id; // The unit ID for normal users stored in products table

                    // **Determine if Normal User or Wholesale**
                    if ($unitId == $normalUserUnitId) {
                        // **Normal User Sale**
                        $outNormal += $quantity;
                        $normalUnitPrice = $unitPriceWithoutTax;
                        $normalTotal += $totalPriceAfterDiscount;
                    } else {
                        // **Wholesale Sale (since unit_id doesn't match the normal user unit)**
                        $outWholesale += $quantity;
                        $wholesaleUnitPrice = $unitPriceWithoutTax;
                        $wholesaleTotal += $totalPriceAfterDiscount;
                    }

                    // **Debugging Log**
                    Log::info("Product {$productId} - Order ID {$order->id}: Unit ID {$unitId}, Normal User Unit ID: {$normalUserUnitId}, Assigned: " . ($unitId == $normalUserUnitId ? "Normal User" : "Wholesale"));
                }
            }
        }

        // **Final Debug Log Before Returning Data**
        Log::info("Final Out Calculation for Product {$productId} - Wholesale: {$outWholesale}, Normal: {$outNormal}");

        return [$outWholesale, $outNormal, $wholesaleUnitPrice, $normalUnitPrice, $wholesaleTotal, $normalTotal];
    }








}
