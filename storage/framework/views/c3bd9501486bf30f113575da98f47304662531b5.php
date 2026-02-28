<?php $__env->startSection('title'); ?>
Orders
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
    }

    .print-hidden {
        margin-bottom: 15px;
    }

    #invoice {
        width: 100%;
        max-width: 900px;
        margin: 20px auto;
        background-color: #fff;
        padding: 40px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    /* Header Section */
    .invoice-header {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
        align-items: flex-start;
    }

    .company-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .company-logo {
        width: 80px;
        height: 80px;
        object-fit: contain;
    }

    .company-details h1 {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .company-details p {
        font-size: 12px;
        line-height: 1.4;
        color: #333;
    }

    .invoice-title {
        text-align: right;
    }

    .invoice-title h2 {
        font-size: 28px;
        font-weight: bold;
        color: #333;
        margin-bottom: 15px;
    }

    .invoice-meta {
        display: flex;
        gap: 30px;
        font-size: 12px;
    }

    .meta-item {
        display: flex;
        gap: 5px;
    }

    .meta-label {
        font-weight: bold;
        min-width: 70px;
    }

    /* Bill To / Ship To Section */
    .bill-ship {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-bottom: 30px;
        border-top: 1px solid #ddd;
        border-bottom: 1px solid #ddd;
        padding: 20px 0;
    }

    .bill-ship h3 {
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .bill-ship p {
        font-size: 12px;
        line-height: 1.6;
        color: #333;
    }

    /* Order Details Table */
    .order-details-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-size: 12px;
    }

    .order-details-table td {
        border: 1px solid #ddd;
        padding: 8px;
        background-color: #fafafa;
    }

    .order-details-table td:first-child {
        font-weight: bold;
        background-color: #f0f0f0;
        width: 20%;
    }

    /* Products Table */
    .products-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-size: 11px;
    }

    .products-table th {
        background-color: #f0f0f0;
        border: 1px solid #999;
        padding: 8px;
        text-align: left;
        font-weight: bold;
        font-size: 11px;
    }

    .products-table td {
        border: 1px solid #ddd;
        padding: 6px 8px;
        text-align: left;
    }

    .products-table td.numeric {
        text-align: right;
    }

    .product-image {
        width: 30px;
        height: 25px;
        object-fit: cover;
    }

    /* Totals Section */
    .totals-section {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-top: 20px;
    }

    .notes-area {
        font-size: 12px;
    }

    .notes-area h4 {
        font-weight: bold;
        margin-bottom: 10px;
    }

    .totals-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    .totals-table tr {
        border-bottom: 1px solid #ddd;
    }

    .totals-table td {
        padding: 6px 0;
    }

    .totals-table td:first-child {
        text-align: left;
        font-weight: 500;
    }

    .totals-table td:last-child {
        text-align: right;
        font-weight: 500;
    }

    .totals-table tr.total-row {
        border-top: 2px solid #333;
        border-bottom: 2px solid #333;
        font-weight: bold;
        font-size: 14px;
    }

    .totals-table tr.total-row td {
        padding: 10px 0;
    }

    /* Print Styles */
    @page {
        size: A4 landscape;
        margin: 5mm;
    }

    @media print {
        body, html {
            margin: 0;
            padding: 0;
            background-color: #fff;
        }

        #invoice {
            max-width: 100%;
            margin: 0;
            padding: 12px;
            box-shadow: none;
            background-color: #fff;
        }

        .invoice-header {
            margin-bottom: 15px;
            gap: 10px;
        }

        .company-logo {
            width: 60px;
            height: 60px;
        }

        .company-details h1 {
            font-size: 14px;
            margin-bottom: 2px;
        }

        .company-details p {
            font-size: 10px;
            line-height: 1.2;
        }

        .invoice-title h2 {
            font-size: 20px;
            margin-bottom: 8px;
        }

        .invoice-meta {
            gap: 15px;
            font-size: 10px;
        }

        .bill-ship {
            gap: 20px;
            margin-bottom: 12px;
            padding: 8px 0;
        }

        .bill-ship h3 {
            font-size: 12px;
            margin-bottom: 5px;
        }

        .bill-ship p {
            font-size: 10px;
            line-height: 1.4;
        }

        .order-details-table {
            margin-bottom: 10px;
            font-size: 10px;
        }

        .order-details-table td {
            padding: 4px;
        }

        .products-table {
            margin-bottom: 10px;
            font-size: 9px;
        }

        .products-table th,
        .products-table td {
            padding: 3px 4px;
        }

        .totals-section {
            gap: 10px;
            margin-top: 8px;
        }

        .notes-area h4 {
            font-size: 10px;
            margin-bottom: 5px;
        }

        .totals-table {
            font-size: 10px;
        }

        .totals-table td {
            padding: 3px 0;
        }

        .totals-table tr.total-row td {
            padding: 5px 0;
            font-size: 11px;
        }

        .print-hidden, .btn, .navbar, .footer, header, footer {
            display: none !important;
        }

        .products-table, .totals-table {
            page-break-inside: avoid;
        }
    }

    /* RTL Support for Arabic */
    <?php if(app()->getLocale() == 'ar'): ?>
        body, #invoice, .invoice-header, .bill-ship, .totals-section {
            direction: rtl;
            text-align: right;
        }

        .company-info {
            flex-direction: row-reverse;
        }

        .invoice-meta {
            flex-direction: row-reverse;
            justify-content: flex-start;
        }

        .products-table th,
        .products-table td,
        .totals-table td {
            text-align: right;
        }

        .products-table td.numeric,
        .totals-table td:last-child {
            text-align: left;
        }
    <?php endif; ?>
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<button onclick="printInvoice()" class="btn btn-sm btn-danger print-hidden">Print Invoice</button>

<div id="invoice">
    <!-- Header Section -->
    <div class="invoice-header">
        <div class="company-info">
            <img src="<?php echo e(asset('assets/admin/imgs/logo.png')); ?>" alt="Company Logo" class="company-logo">
            <div class="company-details">
                <h1>CALIFORNIA CASH AND CARRY</h1>
                <p>791 Oller Street</p>
                <p>Mendota, CA United States 93640</p>
                <p>(800) 000-0000</p>
            </div>
        </div>
        <div class="invoice-title">
            <h2>INVOICE</h2>
            <div class="invoice-meta">
                <div class="meta-item">
                    <span class="meta-label">DATE</span>
                    <span><?php echo e(\Carbon\Carbon::parse($order->date)->format('m/d/Y')); ?></span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Invoice #</span>
                    <span><?php echo e($order->number); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bill To / Ship To Section -->
    <div class="bill-ship">
        <div>
            <h3>Bill To:</h3>
            <p><?php echo e($order->user->name ?? 'N/A'); ?></p>
            <p><?php echo e($order->user->phone ?? ''); ?></p>
        </div>
        <div>
            <h3>Ship To:</h3>
            <?php if($order->address): ?>
                <p><?php echo e($order->address->address ?? 'N/A'); ?></p>
                <p><?php echo e($order->address->street ?? ''); ?></p>
                <p><?php echo e($order->address->building_number ?? ''); ?></p>
            <?php else: ?>
                <p>N/A</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Order Details -->
    <table class="order-details-table">
        <tr>
            <td>P.O.Number</td>
            <td>Terms</td>
            <td>Pickup/Local Delivery Time</td>
            <td>Sales Outlet</td>
        </tr>
        <tr>
            <td>-</td>
            <td>Due on Receipt</td>
            <td>-</td>
            <td>California Cash and Carry</td>
        </tr>
    </table>

    <!-- Products Table -->
    <table class="products-table">
        <thead>
            <tr>
                <th style="width: 25%;">Description</th>
                <th style="width: 15%;">Note</th>
                <th style="width: 10%;">Size</th>
                <th style="width: 12%;">SKU</th>
                <th style="width: 10%;">Quantity</th>
                <th style="width: 10%;text-align: right;">Price</th>
                <th style="width: 8%;text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $order->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($product->name_en); ?></td>
                <td>-</td>
                <td>
                    <?php if($product->pivot->unit_id): ?>
                        <?php echo e(\App\Models\Unit::find($product->pivot->unit_id)->name_en); ?>

                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td><?php echo e($product->sku ?? $product->id); ?></td>
                <td class="numeric"><?php echo e($product->pivot->quantity); ?></td>
                <td class="numeric">$<?php echo e(number_format($product->pivot->total_price_before_tax / $product->pivot->quantity, 2)); ?></td>
                <td class="numeric">$<?php echo e(number_format($product->pivot->total_price_after_tax, 2)); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <!-- Totals Section -->
    <div class="totals-section">
        <div class="notes-area">
            <h4>Notes:</h4>
        </div>
        <table class="totals-table">
            <tr>
                <td>Sales Tax</td>
                <td>$<?php echo e(number_format($order->total_taxes, 2)); ?></td>
            </tr>
            <tr>
                <td>Shipping Amount</td>
                <td>$<?php echo e(number_format($order->delivery_fee, 2)); ?></td>
            </tr>
            <tr>
                <td>Sub Total</td>
                <?php
                    $subtotal = $order->total_prices - $order->delivery_fee;
                ?>
                <td>$<?php echo e(number_format($subtotal, 2)); ?></td>
            </tr>
            <tr class="total-row">
                <td>Total:</td>
                <td>$<?php echo e(number_format($order->total_prices, 2)); ?></td>
            </tr>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    function printInvoice() {
        window.print();
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u167651649/domains/mutasemjaber.online/public_html/mercaso/resources/views/admin/orders/show.blade.php ENDPATH**/ ?>