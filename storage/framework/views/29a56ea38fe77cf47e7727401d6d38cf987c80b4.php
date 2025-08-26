<?php $__env->startSection('title'); ?>
<?php echo e(__('messages.order_report')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-3 text-gray-800"><?php echo e(__('messages.order_report')); ?></h1>
            <form method="GET" action="<?php echo e(route('order_report')); ?>">
                <div class="form-row align-items-end">
                 
                    <div class="form-group col-md-3">
                        <label for="user_type"><?php echo e(__('messages.user_type')); ?></label>
                        <select id="user_type" name="user_type" class="form-control">
                            <option value=""><?php echo e(__('messages.select_user_type')); ?></option>
                            <option value="1" <?php echo e(request('user_type') == 1 ? 'selected' : ''); ?>><?php echo e(__('messages.User')); ?></option>
                            <option value="2" <?php echo e(request('user_type') == 2 ? 'selected' : ''); ?>><?php echo e(__('messages.WholeSale')); ?></option>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="order_status"><?php echo e(__('messages.order_status')); ?></label>
                        <select id="order_status" name="order_status" class="form-control">
                            <option value=""><?php echo e(__('messages.select_status')); ?></option>
                            <option value="1" <?php echo e(request('order_status') == 1 ? 'selected' : ''); ?>><?php echo e(__('messages.Pending')); ?></option>
                            <option value="2" <?php echo e(request('order_status') == 2 ? 'selected' : ''); ?>><?php echo e(__('messages.Accepted')); ?></option>
                            <option value="3" <?php echo e(request('order_status') == 3 ? 'selected' : ''); ?>><?php echo e(__('messages.OnTheWay')); ?></option>
                            <option value="4" <?php echo e(request('order_status') == 4 ? 'selected' : ''); ?>><?php echo e(__('messages.Delivered')); ?></option>
                            <option value="5" <?php echo e(request('order_status') == 5 ? 'selected' : ''); ?>><?php echo e(__('messages.Canceled')); ?></option>
                            <option value="6" <?php echo e(request('order_status') == 6 ? 'selected' : ''); ?>><?php echo e(__('messages.Refund')); ?></option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="from_date"><?php echo e(__('messages.From_Date')); ?></label>
                        <input type="date" id="from_date" name="from_date" class="form-control" value="<?php echo e(request('from_date')); ?>">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="to_date"><?php echo e(__('messages.To_Date')); ?></label>
                        <input type="date" id="to_date" name="to_date" class="form-control" value="<?php echo e(request('to_date', date('Y-m-d'))); ?>">
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
                    <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('messages.order_report')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('messages.number')); ?></th>
                                    <th><?php echo e(__('messages.User')); ?></th>
                                    <th><?php echo e(__('messages.total_prices')); ?></th>
                                    <th><?php echo e(__('messages.order_status')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $reportData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($data['order_id']); ?></td>
                                    <td><?php echo e($data['user']); ?></td>
                                    <td><?php echo e($data['total_prices']); ?></td>
                                    <td><?php echo e($data['order_status']); ?></td>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\mercaso\resources\views/reports/order_report.blade.php ENDPATH**/ ?>