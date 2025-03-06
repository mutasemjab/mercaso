<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-3 text-gray-800"><?php echo e(__('messages.product_move')); ?></h1>
            <form method="GET" action="<?php echo e(route('product_move_all')); ?>">
                <div class="form-row align-items-end">
                    <div class="form-group col-md-3">
                        <label for="shop_id"><?php echo e(__('messages.shop')); ?></label>
                        <select id="shop_id" name="shop_id" class="form-control" required>
                            <option value=""><?php echo e(__('messages.select_shop')); ?></option>
                            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($shop->id); ?>" <?php echo e(request('shop_id') == $shop->id ? 'selected' : ''); ?>>
                                    <?php echo e($shop->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="from_date"><?php echo e(__('messages.from_date')); ?></label>
                        <input type="date" id="from_date" name="from_date" class="form-control" value="<?php echo e(request('from_date', date('Y-m-01'))); ?>">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="to_date"><?php echo e(__('messages.to_date')); ?></label>
                        <input type="date" id="to_date" name="to_date" class="form-control" value="<?php echo e(request('to_date', date('Y-m-t'))); ?>">
                    </div>

                    <div class="form-group col-md-3">
                        <button type="submit" class="btn btn-primary"><?php echo e(__('messages.Show')); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if(!empty($reportData)): ?>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><?php echo e(__('messages.product_name')); ?></th>
                    <th><?php echo e(__('messages.beginning_balance')); ?></th>
                    <th><?php echo e(__('messages.purchase_price')); ?></th>
                    <th><?php echo e(__('messages.in_quantity')); ?></th>
                    <th><?php echo e(__('messages.out_wholesale')); ?></th>
                    <th><?php echo e(__('messages.wholesale_unit_price')); ?></th>
                    <th><?php echo e(__('messages.wholesale_total')); ?></th>
                    <th><?php echo e(__('messages.out_normal')); ?></th>
                    <th><?php echo e(__('messages.normal_unit_price')); ?></th>
                    <th><?php echo e(__('messages.normal_total')); ?></th>
                    <th><?php echo e(__('messages.remaining')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $reportData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($productData['product_name']); ?></td>
                        <td><?php echo e($productData['beginning_balance']); ?></td>
                        <td><?php echo e(number_format($productData['purchase_price'], 2)); ?></td>
                        <td><?php echo e($productData['in_quantity']); ?></td>
                        <td><?php echo e($productData['out_wholesale']); ?></td>
                        <td><?php echo e(number_format($productData['wholesale_unit_price'], 2)); ?></td>
                        <td><?php echo e(number_format($productData['wholesale_total'], 2)); ?></td>
                        <td><?php echo e($productData['out_normal']); ?></td>
                        <td><?php echo e(number_format($productData['normal_unit_price'], 2)); ?></td>
                        <td><?php echo e(number_format($productData['normal_total'], 2)); ?></td>
                        <td><?php echo e(number_format($productData['remaining'], 2)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <p class="text-center"><?php echo e(__('messages.no_data_available')); ?></p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    $(document).ready(function() {
        $('#shop_id').select2({
            placeholder: 'Select Shop',
            allowClear: true
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\vertex\resources\views/reports/all_products.blade.php ENDPATH**/ ?>