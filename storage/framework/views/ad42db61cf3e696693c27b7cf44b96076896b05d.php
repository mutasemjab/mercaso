<?php $__env->startSection('title'); ?>
<?php echo e(__('messages.deliveries')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> <?php echo e(__('messages.deliveries')); ?> </h3>
        <input type="hidden" id="token_search" value="<?php echo e(csrf_token()); ?>">
        <a href="<?php echo e(route('deliveries.create')); ?>" class="btn btn-sm btn-success"> <?php echo e(__('messages.New')); ?> <?php echo e(__('messages.deliveries')); ?></a>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                
            </div>
        </div>
        <div class="clearfix"></div>

        <div id="ajax_responce_serarchDiv" class="col-md-12">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('order-table')): ?>
            <?php if(isset($data) && !$data->isEmpty()): ?>
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">
                    <th><?php echo e(__('messages.Place')); ?></th>
                    <th><?php echo e(__('messages.Price')); ?></th>
                    <th>Zip Code</th>
                    <th>Availabilities</th>
                    <th><?php echo e(__('messages.Action')); ?></th>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($info->place); ?></td>
                        <td>$<?php echo e(number_format($info->price, 2)); ?></td>
                        <td><?php echo e($info->zip_code); ?></td>
                        <td>
                            <?php if($info->availabilities && $info->availabilities->count() > 0): ?>
                                <?php
                                    $availabilitiesByDay = $info->availabilities->groupBy('day_of_week');
                                ?>
                                <div class="availability-summary">
                                    <?php $__currentLoopData = $availabilitiesByDay; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day => $dayAvailabilities): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="day-availability mb-1">
                                            <strong><?php echo e(ucfirst($day)); ?>:</strong>
                                            <?php $__currentLoopData = $dayAvailabilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $availability): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge badge-<?php echo e($availability->is_active ? 'success' : 'secondary'); ?> mr-1">
                                                    <?php echo e($availability->time_from->format('H:i')); ?> - <?php echo e($availability->time_to->format('H:i')); ?>

                                                </span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <span class="text-muted">No availabilities set</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delivery-edit')): ?>
                            <a href="<?php echo e(route('deliveries.edit', $info->id)); ?>" class="btn btn-sm btn-primary mb-1"><?php echo e(__('messages.Edit')); ?></a>
                            <a href="<?php echo e(route('deliveries.availabilities', $info->id)); ?>" class="btn btn-sm btn-info mb-1">
                                <i class="fas fa-clock"></i> Manage Schedule
                            </a>
                            <?php endif; ?>
                            
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delivery-delete')): ?>
                            <form action="<?php echo e(route('deliveries.destroy', $info->id)); ?>" method="POST" style="display: inline-block;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Are you sure you want to delete this delivery?')">
                                    <?php echo e(__('messages.Delete')); ?>

                                </button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <br>
            <?php echo e($data->links()); ?>


            <?php else: ?>
            <div class="alert alert-danger">
                <?php echo e(__('messages.No_data')); ?>

            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.availability-summary {
    max-width: 300px;
}
.day-availability {
    font-size: 0.875rem;
    line-height: 1.4;
}
.badge {
    font-size: 0.75rem;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/admin/js/deliveriess.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\mercaso\resources\views/admin/deliveries/index.blade.php ENDPATH**/ ?>