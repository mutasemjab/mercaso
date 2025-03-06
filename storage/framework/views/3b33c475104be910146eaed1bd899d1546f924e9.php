

<?php $__env->startSection('title'); ?>
<?php echo e(__('messages.inventory_report_with_costs')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="my-4">Inventory Report</h1>
    
    <?php if(!isset($isExport)): ?> <!-- Only for the web view -->
        <form method="GET" action="<?php echo e(route('inventory_report')); ?>" class="mb-4">
            <div class="form-group">
                <label for="shop_id">Shop:</label>
                <select id="shop_id" name="shop_id" class="form-control" required>
                    <option value="">Select Shop</option>
                    <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($shop->id); ?>" <?php echo e((old('shop_id') ?? $shopId) == $shop->id ? 'selected' : ''); ?>>
                            <?php echo e($shop->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group">
                <label for="to_date">To Date:</label>
                <input type="date" id="to_date" name="to_date" class="form-control" value="<?php echo e(old('to_date', $toDate)); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Generate Report</button>
        </form>

        <?php if(!empty($reportData)): ?>
            <!-- Export to Excel button -->
            <form method="GET" action="<?php echo e(route('inventory_report.export')); ?>" class="mb-4">
                <input type="hidden" name="shop_id" value="<?php echo e($shopId); ?>">
                <input type="hidden" name="to_date" value="<?php echo e($toDate); ?>">
                <button type="submit" class="btn btn-success">Export to Excel</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Simplified Table for Excel Export -->
    <table class="table table-striped">
        <thead class="thead-dark">
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
                <td colspan="3"><span style="color:red;">Total</span></td>
                <td> <span style="color:red;"><?php echo e(number_format($totalPurchasingValue, 3)); ?></span></td>
            </tr>
        </tfoot>
    </table>

    <?php if(empty($reportData)): ?>
        <p>No data available for the selected criteria.</p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\vertex\resources\views/reports/inventory_report.blade.php ENDPATH**/ ?>