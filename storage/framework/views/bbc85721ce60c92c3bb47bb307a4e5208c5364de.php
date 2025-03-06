<?php $__env->startSection('title'); ?>
<?php echo e(__('messages.products')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-3 text-gray-800"><?php echo e(__('messages.products')); ?></h1>
            <form method="GET" action="<?php echo e(route('product_report')); ?>">
                <div class="form-row align-items-end">
                    <div class="form-group col-md-3">
                        <label for="shop_id"><?php echo e(__('messages.shop')); ?></label>
                        <select id="shop_id" name="shop_id" class="form-control" required>
                            <option value=""><?php echo e(__('messages.select_shop')); ?></option>
                            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($shop->id); ?>" <?php echo e(request('shop_id') == $shop->id ? 'selected' : ''); ?>><?php echo e($shop->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
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
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.products')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <h3>
                            <?php echo e(__('messages.products')); ?>

                            <span class="badge badge-info"><?php echo e($reportData['products']->count()); ?></span>
                        </h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('messages.ID')); ?></th>
                                    <th><?php echo e(__('messages.Name')); ?></th>
                                    <th><?php echo e(__('messages.Status')); ?></th>
                                    <th><?php echo e(__('messages.Category')); ?></th>
                                    <th><?php echo e(__('messages.UnitForNormalUser')); ?></th>
                                    <th><?php echo e(__('messages.PriceForNormalUser')); ?></th>
                                    <th><?php echo e(__('messages.UnitForWholeSale')); ?></th>
                                    <th><?php echo e(__('messages.PriceForWholeSale')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $reportData['products']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><?php echo e($product->name_en); ?></td>
                                    <td><?php echo e($product->status == 1 ? 'Active' : 'Not Active'); ?></td>
                                    <td><?php echo e($product->category->name_en ?? null); ?></td>
                                    <td><?php echo e($product->unit->name_en ?? null); ?></td>
                                    <td><?php echo e($product->selling_price_for_user ?? null); ?></td>
                                    <td><?php echo e($product->units->first()->name_en ?? null); ?></td>
                                    <td><?php echo e($product->units->first()->pivot->selling_price ?? null); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u573862697/domains/vertex-jordan.online/public_html/resources/views/reports/products.blade.php ENDPATH**/ ?>