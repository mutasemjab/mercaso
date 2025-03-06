
<?php $__env->startSection('title'); ?>
Orders
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    #invoice {
        width: 100%;
        max-width: 100%;
        margin: auto;
        border: 1px solid #ccc;
        padding: 20px;
        background-color: #fff;
        box-sizing: border-box;
    }

    .custom_photo {
        width: 50px;
        height: 35px;
        object-fit: cover;
    }

    #header {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        margin-bottom: -50px;
    }

    #logo {
        max-width: 100px;
        margin-bottom: 10px;
        margin: 0 auto;
    }

    #company-name {
        font-size: 1.5em;
        font-weight: bold;
        text-align: center;
        margin-bottom: 10px;
    }

<?php if(app()->getLocale() == 'ar'): ?>

    #details {
    display: flex;
    justify-content: space-between; 
    align-items: flex-start; 
    margin-top: 20px;
    direction: ltr; 
    }

    #details-left {
        flex-basis: 48%;
        text-align: right; 
    }

    #details-right {
        flex-basis: 48%;
        text-align: left; 
    }
    
    #details-left p,
    #details-right p {
        margin: 0; /* Removes all margins around the paragraphs */
        margin-bottom: 5px; /* Adds a smaller bottom margin to create a bit of space */
    }
<?php else: ?>
    #details {
    display: flex;
    justify-content: space-between; 
    align-items: flex-start; 
    margin-top: 20px;
    direction: ltr;
    }

    #details-left {
        text-align: left;
        flex-basis: 48%;
    }

    #details-right {
        text-align: right;
        flex-basis: 48%;
    }
    #details-left p,
#details-right p {
    margin: 0; /* Removes all margins around the paragraphs */
    margin-bottom: 5px; /* Adds a smaller bottom margin to create a bit of space */
}
<?php endif; ?>



    p.inoice-d-address {
    direction: ltr !important;
}


    #client-details {
        margin-top: 30px;
    }

    #client-details p {
        text-align: left;
        margin: 0;
    }

    #products {
        margin-top: 30px;
        width: 100%;
        border-collapse: collapse;
        table-layout: auto; /* Allows columns to adjust based on content */
    }

    #products th, #products td {
        border: 1px solid #ddd;
        padding: 4px;
        text-align: center;
        box-sizing: border-box;
        word-wrap: break-word;
        font-size: 0.8em;
    }

    #products th {
        background-color: #f5f5f5;
        font-weight: bold;
    }

    #totals {
        margin-top: 20px;
        text-align: left;
        font-size: 1.2em;
    }

    #totals div {
        margin-bottom: 10px;
    }

    @page {
        size: A4 landscape; 
        margin: 5mm;
    }

    @media print {
        body, html {
            width: 297mm; 
            height: 210mm; 
            margin: 0;
            padding: 0;
        }

        #invoice {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            border: none;
            background-color: #fff;
            box-sizing: border-box;
        }

        #products th, #products td {
            font-size: 0.75em;
            padding: 3px; /* Further reduced padding for printing */
        }

        tr, td {
            page-break-inside: avoid;
        }

        .print-hidden, .btn, .navbar, .footer, header, footer {
            display: none !important;
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<button onclick="printInvoice()" class="btn btn-sm btn-danger print-hidden">Print Invoice</button>

<div id="invoice">
    <div id="header">
        <img id="logo" src="<?php echo e(asset('assets/admin/imgs/logo.png')); ?>" alt="Company Logo">
        <div id="company-name">Kertas Company</div>
    </div>
    <br>
    <br>
    <?php if($order->order_type == 2): ?>
    <h4 style="text-align: center !important;">Return</h4>
    <?php endif; ?>

    <div id="details">
        <div id="details-left">
            <p>Date: <?php echo e(\Carbon\Carbon::parse($order->date)->format('Y-m-d')); ?></p>
            <p>Invoice #: <?php echo e($order->number); ?></p>
            <p>Tax Number: 12354866 </p>
        </div>
        <div id="details-right">
            <p>Client: <?php echo e($order->user->name); ?></p>
            <p class="inoice-d-address">Address: <?php echo e($order->address->address); ?> / Street: <?php echo e($order->address->street); ?> <br> Building Number: <?php echo e($order->address->building_number); ?></p>
        </div>
    </div>

    <table id="products">
        <thead>
            <tr>
                <th>Image</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Unit Description</th>
                <th>Unit Price Before Tax</th>
                <th>Price Before Tax</th>
                <th>Discount Percentage</th>
                <th>Discount Value</th>
                <th>Tax</th>
                <th>Tax Amount</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $order->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td>
                    <?php if($product->productImages->first() && $product->productImages->first()->photo): ?>
                    <div class="image">
                        <img class="custom_photo" src="<?php echo e(asset('assets/admin/uploads').'/'.$product->productImages->first()->photo); ?>">
                    </div>
                    <?php else: ?>
                    No Photo
                    <?php endif; ?>
                </td>
                <td><?php echo e($product->name_en); ?></td>
               
                <td><?php echo e($product->pivot->quantity); ?></td>
                <td>
                    <?php if($product->pivot->unit_id): ?>
                    <?php echo e(\App\Models\Unit::find($product->pivot->unit_id)->name_en); ?>

                    <?php else: ?>
                    None
                    <?php endif; ?>
                </td>
                <td><?php echo e(round($product->pivot->total_price_before_tax / $product->pivot->quantity, 3)); ?></td>
                <td><?php echo e(round($product->pivot->total_price_before_tax, 3)); ?></td>
                
                  
                <td><?php echo e($product->pivot->line_discount_percentage ?? 0); ?> %</td>
                  
                  
                  
                <td><?php echo e(round($product->pivot->line_discount_value,3) ?? 0); ?></td>
                  
                   
                <td><?php echo e($product->pivot->tax_percentage); ?> %</td>
               <?php
                $discounted_price = $product->pivot->total_price_before_tax - $product->pivot->line_discount_value; 
                $price_after_coupon = $discounted_price - ($discounted_price * ($order->coupon_discount / 100));
                $tax_amount = $price_after_coupon * ($product->pivot->tax_percentage / 100);
            ?>
               <td><?php echo e(round($tax_amount, 3)); ?></td>

                <td><?php echo e(round($product->pivot->total_price_after_tax, 3)); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div id="totals">
        <?php
        $totalPriceBeforeTax = $order->products->sum(function ($product) {
            return $product->pivot->total_price_before_tax;
        });
        ?>
        <div>Total Before Tax: <?php echo e(round($totalPriceBeforeTax, 3)); ?> JD</div>

        <div>
            <?php if($order->total_discounts): ?>
            <p class="total-label" style="color: red">Discount: - <?php echo e(round($order->total_discounts,3)); ?> JD</p>
            <?php endif; ?>
        </div>

        <?php
        $taxGroups = $order->products->groupBy('pivot.tax_percentage')->map(function ($group) {
            return $group->sum('pivot.tax_value');
        });
        ?>

        <?php $__currentLoopData = $taxGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $taxPercentage => $taxValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div>Tax (<?php echo e($taxPercentage); ?>%): <?php echo e(round($taxValue, 3)); ?> JD</div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <div>Delivery Fee: <?php echo e($order->delivery_fee); ?> JD</div>

        <div>Total: <?php echo e(round($order->total_prices, 3)); ?> JD</div>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u573862697/domains/vertex-jordan.online/public_html/resources/views/admin/orders/show.blade.php ENDPATH**/ ?>