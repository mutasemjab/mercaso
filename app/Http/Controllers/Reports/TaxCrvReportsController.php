<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesTaxReportExport;
use App\Exports\CrvReportExport;
use App\Exports\CombinedTaxCrvReportExport;
use App\Exports\MonthlyTaxSummaryExport;
use Illuminate\Support\Facades\Log;

class TaxCrvReportsController extends Controller
{
    /**
     * Display tax and CRV reports dashboard
     */
    public function index()
    {
        Log::info('TaxCrvReportsController: index() called');
        return view('reports.tax-crv');
    }

    /**
     * Get dashboard statistics with comprehensive logging
     */
    public function getDashboardStats(Request $request)
    {
        Log::info('TaxCrvReportsController: getDashboardStats() called');

        try {
            $currentDate = now();
            $startOfMonth = $currentDate->copy()->startOfMonth();
            $endOfMonth = $currentDate->copy()->endOfMonth();

            Log::info('Dashboard Stats Date Range', [
                'current_date' => $currentDate->format('Y-m-d H:i:s'),
                'start_of_month' => $startOfMonth->format('Y-m-d H:i:s'),
                'end_of_month' => $endOfMonth->format('Y-m-d H:i:s')
            ]);

            // Method 1: Try to get orders with the correct relationship name
            // First, let's determine what the actual relationship is called
            try {
                // This will help us understand your model structure
                $sampleOrder = Order::first();
                if ($sampleOrder) {
                    Log::info('Sample Order Relationships', [
                        'order_id' => $sampleOrder->id,
                        'available_relations' => array_keys($sampleOrder->getRelations())
                    ]);
                }
            } catch (\Exception $e) {
                Log::warning('Could not get sample order: ' . $e->getMessage());
            }

            // Method 2: Use direct SQL to avoid relationship issues
            $sqlOrderCount = DB::select("
                SELECT COUNT(*) as count 
                FROM orders 
                WHERE created_at >= ? AND created_at <= ? AND order_status != 5
            ", [$startOfMonth, $endOfMonth]);

            Log::info('Raw SQL Order Count', [
                'count' => $sqlOrderCount[0]->count ?? 0
            ]);

            // Method 3: Get tax data directly with SQL
            $sqlTaxData = DB::select("
                SELECT 
                    SUM(op.tax_value) as total_tax,
                    COUNT(DISTINCT o.id) as order_count
                FROM orders o 
                LEFT JOIN order_products op ON o.id = op.order_id 
                WHERE o.created_at >= ? AND o.created_at <= ? 
                AND o.order_status != 5
            ", [$startOfMonth, $endOfMonth]);

            // Method 4: Get CRV data directly with SQL  
            $sqlCrvData = DB::select("
                SELECT SUM(p.crv * op.quantity) as total_crv
                FROM orders o 
                JOIN order_products op ON o.id = op.order_id 
                JOIN products p ON p.id = op.product_id 
                WHERE o.created_at >= ? AND o.created_at <= ? 
                AND o.order_status != 5 
                AND p.crv > 0
            ", [$startOfMonth, $endOfMonth]);

            $totalTax = $sqlTaxData[0]->total_tax ?? 0;
            $totalCrv = $sqlCrvData[0]->total_crv ?? 0;
            $totalOrders = $sqlTaxData[0]->order_count ?? 0;

            Log::info('SQL Results', [
                'total_tax' => $totalTax,
                'total_crv' => $totalCrv,
                'total_orders' => $totalOrders
            ]);

            // Method 5: Get sample data for debugging
            $sampleOrderData = DB::select("
                SELECT 
                    o.id as order_id,
                    o.number as order_number,
                    o.created_at,
                    o.order_status,
                    op.product_id,
                    op.quantity,
                    op.tax_value,
                    op.tax_percentage,
                    p.name_en as product_name,
                    p.crv as product_crv
                FROM orders o 
                LEFT JOIN order_products op ON o.id = op.order_id 
                LEFT JOIN products p ON p.id = op.product_id
                WHERE o.created_at >= ? AND o.created_at <= ? 
                AND o.order_status != 5
                LIMIT 5
            ", [$startOfMonth, $endOfMonth]);

            Log::info('Sample Order Data', [
                'sample_count' => count($sampleOrderData),
                'sample_data' => $sampleOrderData
            ]);

            $nextDueDate = $currentDate->copy()->addMonth()->day(20);

            $response = [
                'current_month_tax' => round((float)$totalTax, 2),
                'current_month_crv' => round((float)$totalCrv, 2),
                'total_orders' => (int)$totalOrders,
                'next_due_date' => $nextDueDate->format('M d, Y'),
                'tax_due_amount' => round((float)$totalTax + (float)$totalCrv, 2)
            ];

            // Add debug info
            if (config('app.debug')) {
                $response['debug'] = [
                    'total_orders_found' => $totalOrders,
                    'sql_tax_total' => $totalTax,
                    'sql_crv_total' => $totalCrv,
                    'sample_data_count' => count($sampleOrderData),
                    'date_range' => [
                        'start' => $startOfMonth->format('Y-m-d H:i:s'),
                        'end' => $endOfMonth->format('Y-m-d H:i:s')
                    ]
                ];
            }

            Log::info('Dashboard Stats Response', $response);

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Dashboard Stats Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => $e->getMessage(),
                'current_month_tax' => 0,
                'current_month_crv' => 0,
                'total_orders' => 0,
                'next_due_date' => now()->addMonth()->day(20)->format('M d, Y'),
                'tax_due_amount' => 0
            ], 500);
        }
    }

    public function salesTaxReport(Request $request)
    {
        Log::info('TaxCrvReportsController: salesTaxReport() called', $request->all());

        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'format' => 'in:json,excel'
            ]);

            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            Log::info('Sales Tax Report Date Range', [
                'start_date' => $startDate->format('Y-m-d H:i:s'),
                'end_date' => $endDate->format('Y-m-d H:i:s'),
                'format' => $request->format
            ]);

            // Use direct SQL to get tax data
            $taxData = DB::select("
                SELECT 
                    o.id as order_id,
                    o.number as order_number,
                    o.created_at as order_date,
                    u.name as customer_name,
                    p.name_en as product_name,
                    op.quantity,
                    op.unit_price,
                    op.total_price_before_tax as sale_amount,
                    op.tax_percentage as tax_rate,
                    op.tax_value as tax_amount,
                    op.total_price_after_tax as total_amount,
                    o.order_status
                FROM orders o
                JOIN order_products op ON o.id = op.order_id
                JOIN products p ON p.id = op.product_id
                LEFT JOIN users u ON u.id = o.user_id
                WHERE o.created_at >= ? AND o.created_at <= ?
                AND o.order_status != 5
                ORDER BY o.created_at DESC
            ", [$startDate, $endDate]);

            Log::info('Tax Data Retrieved', [
                'count' => count($taxData),
                'sample' => array_slice($taxData, 0, 3)
            ]);

            // Process the data
            $processedTaxData = [];
            $totalSales = 0;
            $totalTaxes = 0;
            $taxBreakdown = [];
            $orderIds = [];

            foreach ($taxData as $row) {
                $taxRate = $row->tax_rate;
                $taxAmount = $row->tax_amount;
                $saleAmount = $row->sale_amount;

                // Group by tax rate
                if (!isset($taxBreakdown[$taxRate])) {
                    $taxBreakdown[$taxRate] = [
                        'tax_rate' => $taxRate,
                        'total_sales' => 0,
                        'total_tax' => 0,
                        'transaction_count' => 0
                    ];
                }

                $taxBreakdown[$taxRate]['total_sales'] += $saleAmount;
                $taxBreakdown[$taxRate]['total_tax'] += $taxAmount;
                $taxBreakdown[$taxRate]['transaction_count']++;

                $totalSales += $saleAmount;
                $totalTaxes += $taxAmount;

                // Track unique orders
                if (!in_array($row->order_id, $orderIds)) {
                    $orderIds[] = $row->order_id;
                }

                $processedTaxData[] = [
                    'order_number' => $row->order_number,
                    'date' => Carbon::parse($row->order_date)->format('Y-m-d'),
                    'customer_name' => $row->customer_name ?? 'Guest',
                    'product_name' => $row->product_name,
                    'quantity' => $row->quantity,
                    'unit_price' => $row->unit_price,
                    'sale_amount' => $saleAmount,
                    'tax_rate' => $taxRate,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $row->total_amount,
                    'order_status' => $this->getOrderStatusText($row->order_status)
                ];
            }

            Log::info('Tax Report Data Processed', [
                'total_sales' => $totalSales,
                'total_taxes' => $totalTaxes,
                'tax_data_count' => count($processedTaxData),
                'unique_orders' => count($orderIds),
                'tax_breakdown_count' => count($taxBreakdown)
            ]);

            $summary = [
                'reporting_period' => [
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d')
                ],
                'totals' => [
                    'total_sales' => round($totalSales, 2),
                    'total_taxes' => round($totalTaxes, 2),
                    'total_orders' => count($orderIds),
                    'total_line_items' => count($processedTaxData)
                ],
                'tax_breakdown' => array_values($taxBreakdown),
                'compliance_note' => 'This report includes all taxable sales for the specified period, excluding canceled orders.'
            ];

            if ($request->format === 'excel') {
                Log::info('Generating Excel Export');
                $fileName = 'sales_tax_report_' . $startDate->format('Y_m_d') . '_to_' . $endDate->format('Y_m_d') . '.xlsx';

                try {
                    return Excel::download(new SalesTaxReportExport($processedTaxData, $summary), $fileName);
                } catch (\Exception $e) {
                    Log::error('Excel Export Error', [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]);

                    // Fallback to CSV if Excel fails
                    $fileName = str_replace('.xlsx', '.csv', $fileName);
                    return $this->generateCSV($processedTaxData, $summary, $fileName);
                }
            }

            Log::info('Returning JSON Response');
            return response()->json([
                'summary' => $summary,
                'transactions' => $processedTaxData
            ]);
        } catch (\Exception $e) {
            Log::error('Sales Tax Report Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all()
            ]);

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate CRV Report with SQL instead of relationships
     */
    public function crvReport(Request $request)
    {
        Log::info('TaxCrvReportsController: crvReport() called', $request->all());

        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'format' => 'in:json,excel'
            ]);

            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            Log::info('CRV Report Date Range', [
                'start_date' => $startDate->format('Y-m-d H:i:s'),
                'end_date' => $endDate->format('Y-m-d H:i:s'),
                'format' => $request->format
            ]);

            // Use direct SQL to get CRV data
            $crvData = DB::select("
                SELECT 
                    o.id as order_id,
                    o.number as order_number,
                    o.created_at as order_date,
                    u.name as customer_name,
                    p.name_en as product_name,
                    p.barcode as product_barcode,
                    op.quantity,
                    p.crv as crv_per_unit,
                    (p.crv * op.quantity) as total_crv,
                    o.order_status
                FROM orders o
                JOIN order_products op ON o.id = op.order_id
                JOIN products p ON p.id = op.product_id
                LEFT JOIN users u ON u.id = o.user_id
                WHERE o.created_at >= ? AND o.created_at <= ?
                AND o.order_status != 5
                AND p.crv > 0
                ORDER BY o.created_at DESC
            ", [$startDate, $endDate]);

            Log::info('CRV Data Retrieved', [
                'count' => count($crvData),
                'sample' => array_slice($crvData, 0, 3)
            ]);

            // Process the data
            $processedCrvData = [];
            $totalCrvCollected = 0;
            $totalUnits = 0;
            $productBreakdown = [];
            $orderIds = [];

            foreach ($crvData as $row) {
                $crvPerUnit = $row->crv_per_unit;
                $quantity = $row->quantity;
                $totalCrvForItem = $row->total_crv;

                // Track by product for summary
                $productKey = $row->product_name . '_' . $row->product_barcode;
                if (!isset($productBreakdown[$productKey])) {
                    $productBreakdown[$productKey] = [
                        'product_name' => $row->product_name,
                        'product_barcode' => $row->product_barcode,
                        'crv_per_unit' => $crvPerUnit,
                        'total_units_sold' => 0,
                        'total_crv_collected' => 0
                    ];
                }

                $productBreakdown[$productKey]['total_units_sold'] += $quantity;
                $productBreakdown[$productKey]['total_crv_collected'] += $totalCrvForItem;

                $totalCrvCollected += $totalCrvForItem;
                $totalUnits += $quantity;

                // Track unique orders
                if (!in_array($row->order_id, $orderIds)) {
                    $orderIds[] = $row->order_id;
                }

                $processedCrvData[] = [
                    'order_number' => $row->order_number,
                    'date' => Carbon::parse($row->order_date)->format('Y-m-d'),
                    'customer_name' => $row->customer_name ?? 'Guest',
                    'product_name' => $row->product_name,
                    'product_barcode' => $row->product_barcode,
                    'quantity' => $quantity,
                    'crv_per_unit' => $crvPerUnit,
                    'total_crv' => $totalCrvForItem,
                    'order_status' => $this->getOrderStatusText($row->order_status)
                ];
            }

            Log::info('CRV Report Data Processed', [
                'total_crv_collected' => $totalCrvCollected,
                'total_units' => $totalUnits,
                'crv_data_count' => count($processedCrvData),
                'unique_orders' => count($orderIds),
                'product_breakdown_count' => count($productBreakdown)
            ]);

            $summary = [
                'reporting_period' => [
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d')
                ],
                'totals' => [
                    'total_crv_collected' => round($totalCrvCollected, 2),
                    'total_units_sold' => $totalUnits,
                    'total_orders_with_crv' => count($orderIds),
                    'unique_crv_products' => count($productBreakdown)
                ],
                'product_breakdown' => array_values($productBreakdown),
                'compliance_note' => 'This report tracks California Redemption Value (CRV) collected on eligible containers.'
            ];

            if ($request->format === 'excel') {
                Log::info('Generating CRV Excel Export');
                $fileName = 'crv_report_' . $startDate->format('Y_m_d') . '_to_' . $endDate->format('Y_m_d') . '.xlsx';

                try {
                    return Excel::download(new CrvReportExport($processedCrvData, $summary), $fileName);
                } catch (\Exception $e) {
                    Log::error('CRV Excel Export Error', [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]);

                    // Fallback to CSV if Excel fails
                    $fileName = str_replace('.xlsx', '.csv', $fileName);
                    return $this->generateCSV($processedCrvData, $summary, $fileName);
                }
            }

            return response()->json([
                'summary' => $summary,
                'transactions' => $processedCrvData
            ]);
        } catch (\Exception $e) {
            Log::error('CRV Report Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all()
            ]);

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate Combined Tax and CRV Report
     */
    public function combinedReport(Request $request)
    {
        Log::info('TaxCrvReportsController: combinedReport() called', $request->all());

        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date'
            ]);

            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            Log::info('Combined Report Date Range', [
                'start_date' => $startDate->format('Y-m-d H:i:s'),
                'end_date' => $endDate->format('Y-m-d H:i:s')
            ]);

            // Get tax data using internal method
            $taxRequest = new Request([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'format' => 'json'
            ]);

            $taxResponse = $this->salesTaxReport($taxRequest);
            $taxData = json_decode($taxResponse->getContent(), true);

            // Get CRV data using internal method  
            $crvRequest = new Request([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'format' => 'json'
            ]);

            $crvResponse = $this->crvReport($crvRequest);
            $crvData = json_decode($crvResponse->getContent(), true);

            Log::info('Combined Report Data Retrieved', [
                'tax_transactions' => count($taxData['transactions'] ?? []),
                'crv_transactions' => count($crvData['transactions'] ?? []),
                'tax_summary' => $taxData['summary']['totals'] ?? [],
                'crv_summary' => $crvData['summary']['totals'] ?? []
            ]);

            $fileName = 'combined_tax_crv_report_' . $startDate->format('Y_m_d') . '_to_' . $endDate->format('Y_m_d');

            // Try Excel first, fallback to CSV
            try {
                // Check if the Export class exists
                if (class_exists('App\Exports\CombinedTaxCrvReportExport')) {
                    Log::info('Attempting Excel export with CombinedTaxCrvReportExport class');
                    return Excel::download(new CombinedTaxCrvReportExport($taxData, $crvData), $fileName . '.xlsx');
                } else {
                    Log::warning('CombinedTaxCrvReportExport class not found, falling back to CSV');
                    throw new \Exception('Excel export class not available');
                }
            } catch (\Exception $e) {
                Log::error('Combined Report Excel Error', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);

                // Generate CSV fallback
                return $this->generateCombinedCSV($taxData, $crvData, $fileName . '.csv');
            }
        } catch (\Exception $e) {
            Log::error('Combined Report Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all()
            ]);

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate Combined CSV file
     */
    private function generateCombinedCSV($taxData, $crvData, $fileName)
    {
        Log::info('Generating combined CSV file', ['filename' => $fileName]);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () use ($taxData, $crvData) {
            $file = fopen('php://output', 'w');

            // Add BOM for Excel compatibility
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Summary Section
            fputcsv($file, ['COMBINED TAX AND CRV REPORT']);
            fputcsv($file, ['Report Generated:', now()->format('Y-m-d H:i:s')]);
            fputcsv($file, []);

            // Tax Summary
            fputcsv($file, ['TAX SUMMARY']);
            if (isset($taxData['summary']['totals'])) {
                foreach ($taxData['summary']['totals'] as $key => $value) {
                    $label = ucwords(str_replace('_', ' ', $key));
                    fputcsv($file, [$label, is_numeric($value) ? '$' . number_format($value, 2) : $value]);
                }
            }
            fputcsv($file, []);

            // CRV Summary
            fputcsv($file, ['CRV SUMMARY']);
            if (isset($crvData['summary']['totals'])) {
                foreach ($crvData['summary']['totals'] as $key => $value) {
                    $label = ucwords(str_replace('_', ' ', $key));
                    fputcsv($file, [$label, is_numeric($value) ? '$' . number_format($value, 2) : $value]);
                }
            }
            fputcsv($file, []);

            // Tax Transactions
            fputcsv($file, ['TAX TRANSACTIONS']);
            if (!empty($taxData['transactions'])) {
                // Headers
                $firstTaxRow = $taxData['transactions'][0];
                fputcsv($file, array_keys($firstTaxRow));

                // Data
                foreach ($taxData['transactions'] as $transaction) {
                    $row = [];
                    foreach ($transaction as $value) {
                        if (is_numeric($value) && !is_string($value)) {
                            $row[] = '$' . number_format($value, 2);
                        } else {
                            $row[] = $value;
                        }
                    }
                    fputcsv($file, $row);
                }
            } else {
                fputcsv($file, ['No tax transactions found']);
            }
            fputcsv($file, []);

            // CRV Transactions
            fputcsv($file, ['CRV TRANSACTIONS']);
            if (!empty($crvData['transactions'])) {
                // Headers
                $firstCrvRow = $crvData['transactions'][0];
                fputcsv($file, array_keys($firstCrvRow));

                // Data
                foreach ($crvData['transactions'] as $transaction) {
                    $row = [];
                    foreach ($transaction as $value) {
                        if (is_numeric($value) && !is_string($value)) {
                            $row[] = '$' . number_format($value, 2);
                        } else {
                            $row[] = $value;
                        }
                    }
                    fputcsv($file, $row);
                }
            } else {
                fputcsv($file, ['No CRV transactions found']);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate Monthly Tax Summary for Government Filing
     */
    public function monthlyTaxSummary(Request $request)
    {
        Log::info('TaxCrvReportsController: monthlyTaxSummary() called', $request->all());

        try {
            $request->validate([
                'year' => 'required|integer|min:2020|max:' . date('Y'),
                'month' => 'required|integer|min:1|max:12',
                'format' => 'in:json,excel'
            ]);

            $year = $request->year;
            $month = $request->month;

            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();

            Log::info('Monthly Summary Date Range', [
                'year' => $year,
                'month' => $month,
                'start_date' => $startDate->format('Y-m-d H:i:s'),
                'end_date' => $endDate->format('Y-m-d H:i:s')
            ]);

            $summary = DB::select("
            SELECT 
                op.tax_percentage as tax_rate,
                COUNT(DISTINCT o.id) as order_count,
                SUM(op.quantity) as total_units,
                SUM(op.total_price_before_tax) as gross_sales,
                SUM(op.tax_value) as tax_collected,
                SUM(op.total_price_after_tax) as total_sales
            FROM orders o
            INNER JOIN order_products op ON o.id = op.order_id
            WHERE o.created_at BETWEEN ? AND ?
            AND o.order_status != 5
            GROUP BY op.tax_percentage
            ORDER BY op.tax_percentage
        ", [$startDate, $endDate]);

            Log::info('Monthly Summary SQL Results', [
                'summary_count' => count($summary),
                'sample' => array_slice($summary, 0, 3)
            ]);

            $monthlyTotal = [
                'reporting_month' => $startDate->format('F Y'),
                'gross_sales' => 0,
                'total_tax_collected' => 0,
                'net_sales' => 0,
                'order_count' => 0
            ];

            foreach ($summary as $row) {
                $monthlyTotal['gross_sales'] += $row->gross_sales;
                $monthlyTotal['total_tax_collected'] += $row->tax_collected;
                $monthlyTotal['net_sales'] += $row->total_sales;
                $monthlyTotal['order_count'] += $row->order_count;
            }

            Log::info('Monthly Total Calculated', $monthlyTotal);

            if ($request->format === 'excel') {
                $fileName = 'monthly_tax_summary_' . $startDate->format('Y_m') . '.xlsx';

                try {
                    return Excel::download(new MonthlyTaxSummaryExport($monthlyTotal, $summary, $startDate), $fileName);
                } catch (\Exception $e) {
                    Log::error('Monthly Summary Excel Error', [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]);

                    // Fallback to CSV
                    $fileName = str_replace('.xlsx', '.csv', $fileName);
                    return $this->generateCSV($summary, $monthlyTotal, $fileName);
                }
            }

            return response()->json([
                'monthly_summary' => $monthlyTotal,
                'tax_rate_breakdown' => $summary,
                'compliance_info' => [
                    'report_generated' => now()->format('Y-m-d H:i:s'),
                    'period' => $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d'),
                    'note' => 'This summary is suitable for monthly tax filing purposes.'
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Monthly Tax Summary Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request' => $request->all()
            ]);

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * CSV fallback generator
     */
    private function generateCSV($data, $summary, $fileName)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
        ];

        $callback = function () use ($data, $summary) {
            $file = fopen('php://output', 'w');

            if (is_array($data) && count($data) > 0) {
                // Write headers based on first row
                $firstRow = is_array($data[0]) ? $data[0] : (array)$data[0];
                fputcsv($file, array_keys($firstRow));

                // Write data
                foreach ($data as $row) {
                    $rowArray = is_array($row) ? $row : (array)$row;
                    fputcsv($file, array_values($rowArray));
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Helper method to get order status text
     */
    private function getOrderStatusText($status)
    {
        $statuses = [
            1 => 'Pending',
            2 => 'Accepted',
            3 => 'On The Way',
            4 => 'Delivered',
            5 => 'Canceled',
            6 => 'Refund'
        ];

        return $statuses[$status] ?? 'Unknown';
    }
}
