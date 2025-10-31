<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TaxCrvReportsController extends Controller
{
    public function index(Request $request)
    {
        // Get date range from request or use defaults
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        // Get sales data grouped by category
        $salesReport = DB::table('order_products')
            ->join('orders', 'order_products.order_id', '=', 'orders.id')
            ->join('products', 'order_products.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.order_status', '!=', 5) // Exclude canceled orders
            ->where('orders.order_type', 1) // Only sales orders
            ->select(
                'categories.id as category_id',
                'categories.name_en as category_name',
                DB::raw('SUM(order_products.quantity) as quantity'),
                DB::raw('SUM(order_products.total_price_before_tax) as sales'),
                DB::raw('SUM(order_products.discount_value + COALESCE(order_products.line_discount_value, 0)) as discount'),
                DB::raw('SUM(order_products.total_price_after_tax) as total'),
                DB::raw('SUM(order_products.tax_value) as tax'),
                DB::raw('SUM(order_products.quantity * products.crv) as crv')
            )
            ->groupBy('categories.id', 'categories.name_en')
            ->orderByDesc('total')
            ->get();

        // Calculate totals
        $totals = [
            'quantity' => $salesReport->sum('quantity'),
            'sales' => $salesReport->sum('sales'),
            'discount' => $salesReport->sum('discount'),
            'total' => $salesReport->sum('total'),
            'tax' => $salesReport->sum('tax'),
            'crv' => $salesReport->sum('crv'),
        ];

        // Calculate percentages and profit
        $salesReport = $salesReport->map(function ($item, $index) use ($totals) {
            $item->row_number = $index + 1;
            $item->cost = 0; // You can add cost calculation if you have cost data
            $item->profit = $item->total; // Profit = Total - Cost
            $item->margin = 100; // Margin percentage
            $item->percentage_of_sales = $totals['total'] > 0 
                ? round(($item->total / $totals['total']) * 100, 1) 
                : 0;
            return $item;
        });

        return view('reports.tax-crv', compact('salesReport', 'totals', 'startDate', 'endDate'));
    }
}
