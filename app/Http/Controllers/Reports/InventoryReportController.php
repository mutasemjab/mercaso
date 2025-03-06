<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\NoteVoucher;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Shop;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InventoryReportExport;
use Illuminate\Support\Facades\Log;

class InventoryReportController extends Controller
{
     public function index(Request $request)
    {
        $shops = Shop::all();
        $shopId = $request->input('shop_id');
        $toDate = $request->input('to_date', date('Y-m-d')); // Default to today's date if not provided

        // Generate report data
        [$reportData, $totalPurchasingValue] = $this->generateReportData($shopId, $toDate);

        return view('reports.inventory_report', compact('shops', 'reportData', 'shopId', 'toDate', 'totalPurchasingValue'));
    }

    public function export(Request $request)
    {
        $shopId = $request->input('shop_id');
        $toDate = $request->input('to_date', date('Y-m-d')); // Default to today's date if not provided

        // Generate report data for export
        [$reportData, $totalPurchasingValue] = $this->generateReportData($shopId, $toDate);

        return Excel::download(new InventoryReportExport($reportData, $totalPurchasingValue, $shopId, $toDate), 'inventory_report.xlsx');
    }



    private function generateReportData($shopId, $toDate)
    {
        $reportData = [];
        $totalPurchasingValue = 0; // Initialize total purchasing value

        if ($shopId) {
            // Fetch note vouchers filtered by shop and date
            $noteVouchers = NoteVoucher::where('shop_id', $shopId)
                ->where('date_note_voucher', '<=', $toDate)
                ->with(['voucherProducts'])
                ->orderBy('date_note_voucher', 'asc') // Ensure correct chronological order
                ->get();

            $productQuantities = [];
            $productCosts = [];
            $lastPurchaseCost = []; // Store last valid purchase price

            foreach ($noteVouchers as $noteVoucher) {
                foreach ($noteVoucher->voucherProducts as $voucherProduct) {
                    $productId = $voucherProduct->id;
                    $unitId = $voucherProduct->pivot->unit_id;
                    $quantity = $voucherProduct->pivot->quantity;
                    $purchasingPrice = $voucherProduct->pivot->purchasing_price;
                    $noteVoucherTypeId = $noteVoucher->note_voucher_type_id;

                    Log::info("Processing Product ID: $productId, Type: $noteVoucherTypeId, Quantity: $quantity, Price: $purchasingPrice");

                    // Convert quantity to the basic unit if necessary
                    $product = Product::find($productId);
                    if ($unitId != $product->unit_id) {
                        $productUnit = $product->units()->where('unit_id', $unitId)->first();
                        if ($productUnit) {
                            $quantity *= $productUnit->pivot->releation;
                        }
                    }

                    if (!isset($productQuantities[$productId])) {
                        $productQuantities[$productId] = 0;
                        $productCosts[$productId] = 0;
                        $lastPurchaseCost[$productId] = 0;
                    }

                    if ($noteVoucherTypeId == 1) { // 'In' without purchasing price (Returns)
                        if ($productQuantities[$productId] == 0) {
                            // If inventory was empty, restore the last stored purchase price
                            $productCosts[$productId] = $lastPurchaseCost[$productId];
                        }
                        $productQuantities[$productId] += $quantity;
                        Log::info("Return Processed: Product ID: $productId, Quantity: {$productQuantities[$productId]}, Cost: {$productCosts[$productId]}");
                    } elseif ($noteVoucherTypeId == 2) { // 'Out'
                        $productQuantities[$productId] -= $quantity;
                        if ($productQuantities[$productId] <= 0) {
                            // Store last purchase cost before setting quantity to zero
                            $lastPurchaseCost[$productId] = $productCosts[$productId];
                            //$productQuantities[$productId] = 0;
                            Log::info("Out Processed: Product ID: $productId, Quantity Sold Out, Last Purchase Cost Stored: {$lastPurchaseCost[$productId]}");
                        }
                    } elseif ($noteVoucherTypeId == 3) { // 'In' with purchasing price (New Purchase)
                        $previousQuantity = $productQuantities[$productId];
                        $previousCost = $productCosts[$productId];
                        $productQuantities[$productId] += $quantity;

                        if ($previousQuantity == 0) {
                            $productCosts[$productId] = $purchasingPrice;
                        } else {
                            $totalPreviousCost = ($previousQuantity * $previousCost);
                            $totalNewCost = ($quantity * $purchasingPrice);
                            $totalQuantity = $previousQuantity + $quantity;
                            if ($totalQuantity > 0) {
                                $productCosts[$productId] = ($totalPreviousCost + $totalNewCost) / $totalQuantity;
                            }
                        }
                        // Update last purchase cost
                        $lastPurchaseCost[$productId] = $purchasingPrice;
                        Log::info("New Purchase Processed: Product ID: $productId, New Cost: {$productCosts[$productId]}");
                    }
                }
            }

            foreach ($productQuantities as $productId => $quantity) {
                $product = Product::find($productId);
                $unit = $product->unit;
                $weightedAverageCost = isset($productCosts[$productId]) ? $productCosts[$productId] : 0;
                $totalProductValue = ($quantity > 0) ? $quantity * $weightedAverageCost : 0;
                $totalPurchasingValue += $totalProductValue;

                Log::info("Final Product Report: Product ID: $productId, Quantity: $quantity, Cost: $weightedAverageCost, Total Value: $totalProductValue");

                $reportData[] = [
                    'product_name' => $product->name_ar ?? 'N/A',
                    'quantity' => $quantity,
                    'unit' => $unit->name_ar ?? 'N/A',
                    'weighted_average_cost' => $weightedAverageCost,
                    'total_value' => $totalProductValue,
                ];
            }
        }

        return [$reportData, $totalPurchasingValue];
    }






}
