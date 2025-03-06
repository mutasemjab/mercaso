<?php $__env->startSection('title'); ?>
<?php echo e(__('messages.tax_report')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-3 text-gray-800"><?php echo e(__('messages.tax_report')); ?></h1>
            <form method="GET" action="<?php echo e(route('tax_report')); ?>">
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
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.tax_report')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('messages.Tax Percentage')); ?></th>
                                    <th><?php echo e(__('messages.Sales Orders Total')); ?></th>
                                    <th><?php echo e(__('messages.Sales Tax Total')); ?></th>
                                    <th><?php echo e(__('messages.Refund Orders Total')); ?></th>
                                    <th><?php echo e(__('messages.Refund Tax Total')); ?></th>
                                    <th><?php echo e(__('messages.Net Orders Total')); ?></th>
                                    <th><?php echo e(__('messages.Net Tax Total')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $reportData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($data['tax']); ?>%</td>
                                    <td><?php echo e(number_format($data['sales'], 2)); ?></td>
                                    <td><?php echo e(number_format($data['tax_amount_sales'], 2)); ?></td>
                                    <td><?php echo e(number_format($data['refund'], 2)); ?></td>
                                    <td><?php echo e(number_format($data['tax_amount_refund'], 2)); ?></td>
                                    <td><?php echo e(number_format($data['net'], 2)); ?></td>
                                    <td><?php echo e(number_format($data['net_tax'], 2)); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5"><?php echo e(__('messages.Total')); ?></th>
                                    <th><?php echo e(number_format($netTotal, 2)); ?></th>
                                    <th><?php echo e(number_format($netTaxTotal, 2)); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u573862697/domains/vertex-jordan.online/public_html/resources/views/reports/tax_report.blade.php ENDPATH**/ ?>