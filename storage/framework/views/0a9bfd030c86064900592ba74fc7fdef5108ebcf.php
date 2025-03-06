<table>
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Unit</th>
            <th>Weighted Average Cost</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $reportData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($data['product_name']); ?></td>
                <td><?php echo e($data['quantity']); ?></td>
                <td><?php echo e($data['unit']); ?></td>
                <td><?php echo e(number_format($data['weighted_average_cost'], 3)); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3">Total</td>
            <td><?php echo e(number_format($totalPurchasingValue, 3)); ?></td>
        </tr>
    </tfoot>
</table>
<?php /**PATH /home/u573862697/domains/vertex-jordan.online/public_html/resources/views/exports/inventory_report_export.blade.php ENDPATH**/ ?>