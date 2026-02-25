<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategoryReportController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('category-report-view')) {
            abort(403, 'Unauthorized action.');
        }

        // Get date range from request or use defaults
        $startDate = $request->input('start_date', now()->subMonth()->startOfDay());
        $endDate = $request->input('end_date', now()->endOfDay());
        $parentCategoryId = $request->input('parent_category_id', null);

        // Get all parent categories for filter dropdown
        $parentCategories = Category::whereNull('category_id')->orderBy('name_ar')->get();

        // Get sales data grouped by category
        $salesData = DB::table('order_products')
            ->join('orders', 'order_products.order_id', '=', 'orders.id')
            ->join('products', 'order_products.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.order_status', '!=', 5) // Exclude canceled orders
            ->where('orders.order_type', 1) // Only sales orders
            ->select(
                'categories.id as category_id',
                'categories.name_ar',
                'categories.name_en',
                'categories.category_id as parent_category_id',
                DB::raw('SUM(order_products.quantity) as total_quantity_sold'),
                DB::raw('SUM(order_products.total_price_before_tax) as total_sales_before_tax'),
                DB::raw('SUM(order_products.total_price_after_tax) as total_sales_after_tax'),
                DB::raw('SUM(order_products.discount_value + COALESCE(order_products.line_discount_value, 0)) as total_discount'),
                DB::raw('SUM(order_products.tax_value) as total_tax'),
                DB::raw('COUNT(DISTINCT order_products.order_id) as total_orders')
            )
            ->groupBy('categories.id', 'categories.name_ar', 'categories.name_en', 'categories.category_id')
            ->get();

        // Get inventory data (current stock per category)
        $inventoryData = DB::table('products')
            ->where('status', 1) // Active products only
            ->select(
                'category_id',
                DB::raw('COUNT(*) as total_products'),
                DB::raw('SUM(CASE WHEN in_stock = 1 THEN 1 ELSE 0 END) as in_stock_count'),
                DB::raw('SUM(CASE WHEN in_stock = 2 THEN 1 ELSE 0 END) as out_of_stock_count')
            )
            ->groupBy('category_id')
            ->get()
            ->keyBy('category_id');

        // Build hierarchical category report with sales and inventory data
        $categoryReport = [];

        // Start with root categories
        $rootCategories = $parentCategoryId
            ? Category::where('id', $parentCategoryId)->get()
            : Category::whereNull('category_id')->orderBy('name_ar')->get();

        foreach ($rootCategories as $rootCategory) {
            $categoryData = $this->buildCategoryHierarchy($rootCategory, $salesData, $inventoryData);
            if ($categoryData) {
                $categoryReport[] = $categoryData;
            }
        }

        // Calculate totals
        $totals = [
            'total_quantity_sold' => $salesData->sum('total_quantity_sold'),
            'total_sales_before_tax' => $salesData->sum('total_sales_before_tax'),
            'total_sales_after_tax' => $salesData->sum('total_sales_after_tax'),
            'total_discount' => $salesData->sum('total_discount'),
            'total_tax' => $salesData->sum('total_tax'),
            'total_orders' => $salesData->sum('total_orders'),
            'total_products' => Product::where('status', 1)->count(),
            'total_in_stock' => Product::where('status', 1)->where('in_stock', 1)->count(),
            'total_out_of_stock' => Product::where('status', 1)->where('in_stock', 2)->count(),
        ];

        return view('reports.category-report', compact(
            'categoryReport',
            'totals',
            'startDate',
            'endDate',
            'parentCategories',
            'parentCategoryId'
        ));
    }

    /**
     * Build hierarchical category data recursively
     */
    private function buildCategoryHierarchy($category, $salesData, $inventoryData, $level = 0)
    {
        $locale = app()->getLocale();
        $nameAttribute = "name_{$locale}";

        // Get sales data for this category
        $sales = $salesData->firstWhere('category_id', $category->id);

        // Get inventory data for this category
        $inventory = $inventoryData[$category->id] ?? null;

        $categoryRow = [
            'id' => $category->id,
            'name' => $category->{$nameAttribute},
            'name_ar' => $category->name_ar,
            'name_en' => $category->name_en,
            'level' => $level,
            'has_children' => $category->childCategories()->count() > 0,
            // Sales data
            'total_quantity_sold' => $sales ? $sales->total_quantity_sold : 0,
            'total_sales_before_tax' => $sales ? $sales->total_sales_before_tax : 0,
            'total_sales_after_tax' => $sales ? $sales->total_sales_after_tax : 0,
            'total_discount' => $sales ? $sales->total_discount : 0,
            'total_tax' => $sales ? $sales->total_tax : 0,
            'total_orders' => $sales ? $sales->total_orders : 0,
            // Inventory data
            'total_products' => $inventory ? $inventory->total_products : 0,
            'in_stock_count' => $inventory ? $inventory->in_stock_count : 0,
            'out_of_stock_count' => $inventory ? $inventory->out_of_stock_count : 0,
            'children' => []
        ];

        // Get child categories
        $childCategories = $category->childCategories()->orderBy('name_ar')->get();
        foreach ($childCategories as $child) {
            $childData = $this->buildCategoryHierarchy($child, $salesData, $inventoryData, $level + 1);
            if ($childData) {
                $categoryRow['children'][] = $childData;
            }
        }

        return $categoryRow;
    }

    /**
     * Export report to Excel
     */
    public function export(Request $request)
    {
        if (!auth()->user()->can('category-report-export')) {
            abort(403, 'Unauthorized action.');
        }

        $startDate = $request->input('start_date', now()->subMonth()->startOfDay());
        $endDate = $request->input('end_date', now()->endOfDay());

        // Get the same data as index()
        $salesData = DB::table('order_products')
            ->join('orders', 'order_products.order_id', '=', 'orders.id')
            ->join('products', 'order_products.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.order_status', '!=', 5)
            ->where('orders.order_type', 1)
            ->select(
                'categories.id',
                'categories.name_ar',
                'categories.name_en',
                'categories.category_id',
                DB::raw('SUM(order_products.quantity) as total_quantity_sold'),
                DB::raw('SUM(order_products.total_price_before_tax) as total_sales_before_tax'),
                DB::raw('SUM(order_products.total_price_after_tax) as total_sales_after_tax'),
                DB::raw('SUM(order_products.discount_value + COALESCE(order_products.line_discount_value, 0)) as total_discount'),
                DB::raw('SUM(order_products.tax_value) as total_tax'),
                DB::raw('COUNT(DISTINCT order_products.order_id) as total_orders')
            )
            ->groupBy('categories.id', 'categories.name_ar', 'categories.name_en', 'categories.category_id')
            ->get();

        $inventoryData = DB::table('products')
            ->where('status', 1)
            ->select(
                'category_id',
                DB::raw('COUNT(*) as total_products'),
                DB::raw('SUM(CASE WHEN in_stock = 1 THEN 1 ELSE 0 END) as in_stock_count'),
                DB::raw('SUM(CASE WHEN in_stock = 2 THEN 1 ELSE 0 END) as out_of_stock_count')
            )
            ->groupBy('category_id')
            ->get()
            ->keyBy('category_id');

        return \Excel::download(
            new \App\Exports\CategoryReportExport($salesData, $inventoryData),
            'category_report_' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}