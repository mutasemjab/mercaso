<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('sales-report-view')) {
            abort(403, __('messages.access_denied'));
        }

        $fromDate   = $request->input('from_date', now()->startOfMonth()->format('Y-m-d'));
        $toDate     = $request->input('to_date', now()->format('Y-m-d'));
        $categoryId = $request->input('category_id');
        $userType   = $request->input('user_type');   // 1=retail, 2=wholesale
        $isPrint    = $request->boolean('print');

        $categories = Category::orderBy('name_ar')->get();

        $startDateTime = Carbon::createFromFormat('Y-m-d', $fromDate)->startOfDay();
        $endDateTime   = Carbon::createFromFormat('Y-m-d', $toDate)->endOfDay();

        // ── Build base query ───────────────────────────────────────────────
        $query = DB::table('order_products')
            ->join('products', 'order_products.product_id', '=', 'products.id')
            ->join('orders',   'order_products.order_id',   '=', 'orders.id')
            ->join('users',    'orders.user_id',            '=', 'users.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.order_type', 1)          // sales only (not refund)
            ->where('orders.order_status', '!=', 5)  // exclude canceled
            ->whereBetween('orders.date', [$startDateTime, $endDateTime])
            ->when($userType,   fn($q) => $q->where('users.user_type',      $userType))
            ->when($categoryId, fn($q) => $q->where('products.category_id', $categoryId))
            ->select(
                'products.id',
                'products.name_ar',
                'products.name_en',
                'categories.name_ar as cat_name_ar',
                'categories.name_en as cat_name_en',
                DB::raw('SUM(order_products.quantity)             as total_quantity'),
                DB::raw('SUM(order_products.total_price_after_tax) as total_revenue'),
                DB::raw('SUM(order_products.tax_value)            as total_tax'),
                DB::raw('SUM(order_products.line_discount_value)  as total_discount'),
                DB::raw('COUNT(DISTINCT orders.id)                as order_count')
            )
            ->groupBy(
                'products.id',
                'products.name_ar',
                'products.name_en',
                'categories.name_ar',
                'categories.name_en'
            )
            ->orderByDesc('total_quantity');

        $products = $query->get();

        // ── Summary totals ─────────────────────────────────────────────────
        $summary = [
            'total_quantity' => $products->sum('total_quantity'),
            'total_revenue'  => $products->sum('total_revenue'),
            'total_tax'      => $products->sum('total_tax'),
            'total_discount' => $products->sum('total_discount'),
            'order_count'    => DB::table('orders')
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->where('orders.order_type', 1)
                ->where('orders.order_status', '!=', 5)
                ->whereBetween('orders.date', [$startDateTime, $endDateTime])
                ->when($userType,   fn($q) => $q->where('users.user_type', $userType))
                ->when($categoryId, fn($q) => $q->whereExists(fn($sub) =>
                    $sub->from('order_products')
                        ->join('products', 'order_products.product_id', '=', 'products.id')
                        ->whereColumn('order_products.order_id', 'orders.id')
                        ->where('products.category_id', $categoryId)
                ))
                ->count(),
        ];

        return view('reports.sales', compact(
            'products', 'summary', 'categories',
            'fromDate', 'toDate', 'categoryId', 'userType', 'isPrint'
        ));
    }
}
