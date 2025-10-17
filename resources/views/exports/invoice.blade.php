<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->number ?? $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
        }
        .company-info {
            text-align: center;
            margin-bottom: 30px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
        }
        .invoice-details {
            margin-bottom: 30px;
            overflow: hidden;
        }
        .customer-info {
            float: left;
            width: 48%;
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
        }
        .order-info {
            float: right;
            width: 48%;
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background-color: white;
        }
        .products-table th {
            background-color: #007bff;
            color: white;
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .products-table td {
            padding: 10px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        .products-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .totals {
            float: right;
            width: 350px;
            margin-top: 20px;
        }
        .totals table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }
        .totals td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .totals .label {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: right;
        }
        .totals .amount {
            text-align: right;
            width: 120px;
        }
        .total-row {
            background-color: #007bff !important;
            color: white !important;
            font-weight: bold;
            font-size: 1.1em;
        }
        .footer {
            clear: both;
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        h1, h2, h3, h4 {
            margin: 0 0 10px 0;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-1 { background-color: #ffc107; color: #000; } /* Pending */
        .status-2 { background-color: #17a2b8; color: #fff; } /* Accepted */
        .status-3 { background-color: #fd7e14; color: #fff; } /* On The Way */
        .status-4 { background-color: #28a745; color: #fff; } /* Delivered */
        .status-5 { background-color: #dc3545; color: #fff; } /* Canceled */
        .status-6 { background-color: #6c757d; color: #fff; } /* Refund */
        
        .payment-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        .payment-1 { background-color: #28a745; color: #fff; } /* Paid */
        .payment-2 { background-color: #dc3545; color: #fff; } /* Unpaid */
    </style>
</head>
<body>
    <div class="header">
        <h1>INVOICE</h1>
        <h2>{{ $order->number ? 'Order #' . $order->number : 'Order #' . $order->id }}</h2>
    </div>

    <div class="company-info">
        <h3>Your Company Name</h3>
        <p>
            Your Company Address<br>
            City, State, ZIP Code<br>
            Phone: (123) 456-7890<br>
            Email: info@yourcompany.com<br>
            Website: www.yourcompany.com
        </p>
    </div>

    <div class="invoice-details clearfix">
        <div class="customer-info">
            <h4>Bill To:</h4>
            <p>
                <strong>{{ $order->user->name }}</strong><br>
                {{ $order->user->email }}<br>
                @if($order->phone_in_order)
                    Phone: {{ $order->phone_in_order }}<br>
                @endif
                @if($order->address)
                    {{ $order->address->street_address }}<br>
                    {{ $order->address->city }}, {{ $order->address->state }} {{ $order->address->postal_code }}<br>
                    {{ $order->address->country }}
                @endif
            </p>
        </div>
        
        <div class="order-info">
            <h4>Order Information:</h4>
            <p>
                <strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}<br>
                @if($order->date)
                    <strong>Delivery Date:</strong> {{ \Carbon\Carbon::parse($order->date)->format('M d, Y') }}<br>
                @endif
                @if($order->from_time && $order->to_time)
                    <strong>Delivery Time:</strong> {{ $order->from_time }} - {{ $order->to_time }}<br>
                @endif
                <strong>Order Status:</strong> 
                @php
                    $statusMap = [
                        1 => 'Pending',
                        2 => 'Accepted', 
                        3 => 'On The Way',
                        4 => 'Delivered',
                        5 => 'Canceled',
                        6 => 'Refund'
                    ];
                    $status = $statusMap[$order->order_status] ?? 'Unknown';
                @endphp
                <span class="status-badge status-{{ $order->order_status }}">{{ $status }}</span><br>
                
                <strong>Payment Method:</strong> {{ ucfirst($order->payment_type) }}<br>
                <strong>Payment Status:</strong> 
                <span class="payment-badge payment-{{ $order->payment_status }}">
                    {{ $order->payment_status == 1 ? 'Paid' : 'Unpaid' }}
                </span><br>
                <strong>Delivery Type:</strong> {{ $order->type_delivery == 1 ? 'Pickup' : 'Delivery' }}
            </p>
        </div>
    </div>

    @if($order->note)
    <div style="margin-bottom: 20px; padding: 15px; background-color: #fff3cd; border-radius: 5px;">
        <strong>Note:</strong> {{ $order->note }}
    </div>
    @endif

    <table class="products-table">
        <thead>
            <tr>
                <th style="width: 30%;">Product</th>
                <th style="width: 12%;">Unit</th>
                <th style="width: 12%;">Variation</th>
                <th style="width: 8%; text-align: center;">Qty</th>
                <th style="width: 10%; text-align: right;">Unit Price</th>
                <th style="width: 10%; text-align: right;">Discount</th>
                <th style="width: 8%; text-align: right;">Tax</th>
                <th style="width: 10%; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderProducts as $orderProduct)
            <tr>
                <td>
                    <strong>{{ $orderProduct->product->name ?? 'N/A' }}</strong>
                    @if($orderProduct->product->category)
                        <br><small style="color: #666;">{{ $orderProduct->product->category->name }}</small>
                    @endif
                </td>
                <td>{{ $orderProduct->unit->name_en ?? 'N/A' }}</td>
                <td>{{ $orderProduct->variation->variation ?? 'Standard' }}</td>
                <td class="text-center">{{ $orderProduct->quantity }}</td>
                <td class="text-right">${{ number_format($orderProduct->unit_price, 2) }}</td>
                <td class="text-right">
                    @if($orderProduct->discount_value > 0)
                        ${{ number_format($orderProduct->discount_value, 2) }}
                        @if($orderProduct->discount_percentage > 0)
                            <br><small>({{ $orderProduct->discount_percentage }}%)</small>
                        @endif
                    @else
                        $0.00
                    @endif
                </td>
                <td class="text-right">
                    ${{ number_format($orderProduct->tax_value, 2) }}
                    @if($orderProduct->tax_percentage > 0)
                        <br><small>({{ $orderProduct->tax_percentage }}%)</small>
                    @endif
                </td>
                <td class="text-right">${{ number_format($orderProduct->total_price_after_tax, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="clearfix">
        <div class="totals">
            <table>
                <tr>
                    <td class="label">Subtotal (Before Tax):</td>
                    <td class="amount">${{ number_format($order->orderProducts->sum('total_price_before_tax'), 2) }}</td>
                </tr>
                @if($order->total_discounts > 0)
                <tr>
                    <td class="label">Total Discounts:</td>
                    <td class="amount">-${{ number_format($order->total_discounts, 2) }}</td>
                </tr>
                @endif
                @if($order->coupon_discount > 0)
                <tr>
                    <td class="label">Coupon Discount:</td>
                    <td class="amount">-${{ number_format($order->coupon_discount, 2) }}</td>
                </tr>
                @endif
                <tr>
                    <td class="label">Total Taxes:</td>
                    <td class="amount">${{ number_format($order->total_taxes, 2) }}</td>
                </tr>
                @if($order->delivery_fee > 0)
                <tr>
                    <td class="label">Delivery Fee:</td>
                    <td class="amount">${{ number_format($order->delivery_fee, 2) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td class="label">Total Amount:</td>
                    <td class="amount">${{ number_format($order->total_prices, 2) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="footer">
        <p><strong>Thank you for your business!</strong></p>
        <p>This is a computer-generated invoice. For questions about this invoice, please contact us.</p>
        <p>Payment Terms: Net 30 days | Late fees may apply for overdue payments</p>
    </div>
</body>
</html>