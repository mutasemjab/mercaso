<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerReportController extends Controller
{
    /**
     * Display the customer report search page
     */
    public function index()
    {
        if (!auth()->user()->can('customer-report-view')) {
            abort(403, 'Unauthorized action.');
        }

        return view('reports.customer-report');
    }

    /**
     * Search for customers (live search)
     */
    public function search(Request $request)
    {
        if (!auth()->user()->can('customer-report-view')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $search = $request->input('search', '');

        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $customers = User::where('name', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->orWhere('phone', 'LIKE', "%{$search}%")
            ->select('id', 'name', 'email', 'phone')
            ->limit(15)
            ->get();

        return response()->json($customers);
    }

    /**
     * Get detailed customer statistics
     */
    public function getCustomerStats(Request $request, $customerId)
    {
        if (!auth()->user()->can('customer-report-view')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $customer = User::find($customerId);

        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        // Get customer basic info
        $customerData = [
            'id' => $customer->id,
            'name' => $customer->name,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'country' => $customer->country ?? 'N/A',
            'city' => $customer->city ?? 'N/A',
            'created_at' => $customer->created_at->format('Y-m-d'),
        ];

        // Get customer orders
        $orders = Order::where('user_id', $customerId)
            ->where('order_status', '!=', 5) // Exclude canceled orders
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $stats = [
            'total_orders' => $orders->count(),
            'total_spent' => $orders->sum('total_prices'),
            'total_discount' => $orders->sum('total_discounts'),
            'total_tax' => $orders->sum('tax_value'),
            'average_order_value' => $orders->count() > 0 ? $orders->sum('total_prices') / $orders->count() : 0,
            'last_order_date' => $orders->first() ? $orders->first()->created_at->format('Y-m-d H:i') : 'N/A',
        ];

        // Get order details
        $orderDetails = [];
        foreach ($orders as $order) {
            $orderDetails[] = [
                'id' => $order->id,
                'number' => $order->number,
                'date' => $order->created_at->format('Y-m-d H:i'),
                'status' => $this->getOrderStatusLabel($order->order_status),
                'status_code' => $order->order_status,
                'total' => $order->total_prices,
                'discount' => $order->total_discounts,
                'tax' => $order->tax_value,
                'payment_status' => $order->payment_status == 1 ? __('messages.paid') : __('messages.unpaid'),
                'delivery_fee' => $order->delivery_fee ?? 0,
            ];
        }

        // Get purchased products
        $products = DB::table('order_products')
            ->join('products', 'order_products.product_id', '=', 'products.id')
            ->join('orders', 'order_products.order_id', '=', 'orders.id')
            ->where('orders.user_id', $customerId)
            ->where('orders.order_status', '!=', 5)
            ->select(
                'products.id',
                'products.name_ar',
                'products.name_en',
                DB::raw('SUM(order_products.quantity) as total_quantity'),
                DB::raw('SUM(order_products.total_price_after_tax) as total_price')
            )
            ->groupBy('products.id', 'products.name_ar', 'products.name_en')
            ->orderBy('total_quantity', 'desc')
            ->get();

        $productDetails = [];
        foreach ($products as $product) {
            $locale = app()->getLocale();
            $nameAttribute = "name_{$locale}";

            $productDetails[] = [
                'id' => $product->id,
                'name' => $product->{$nameAttribute},
                'quantity' => $product->total_quantity,
                'total_price' => $product->total_price,
            ];
        }

        return response()->json([
            'customer' => $customerData,
            'stats' => $stats,
            'orders' => $orderDetails,
            'products' => $productDetails,
        ]);
    }

    /**
     * Get readable order status label
     */
    private function getOrderStatusLabel($statusCode)
    {
        $statuses = [
            1 => __('messages.order_status_pending'),
            2 => __('messages.order_status_accepted'),
            3 => __('messages.order_status_ontheway'),
            4 => __('messages.order_status_delivered'),
            5 => __('messages.order_status_canceled'),
            6 => __('messages.order_status_refund'),
        ];

        return $statuses[$statusCode] ?? 'Unknown';
    }
}
