<?php $__env->startSection('title'); ?>
<?php echo e(__('messages.deliveries')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> <?php echo e(__('messages.New')); ?> <?php echo e(__('messages.deliveries')); ?> </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <form action="<?php echo e(route('deliveries.store')); ?>" method="post" enctype='multipart/form-data'>
            <div class="row">
                <?php echo csrf_field(); ?>

                <div class="col-md-4">
                    <div class="form-group">
                        <label> <?php echo e(__('messages.Place')); ?> </label>
                        <input name="place" id="place" class="form-control" value="<?php echo e(old('place')); ?>" required>
                        <?php $__errorArgs = ['place'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label> <?php echo e(__('messages.Price')); ?> </label>
                        <input name="price" id="price" type="number" step="any" class="form-control" value="<?php echo e(old('price')); ?>" required>
                        <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            
                <div class="col-md-4">
                    <div class="form-group">
                        <label> Zip Code</label>
                        <input name="zip_code" id="zip_code" type="number" step="any" class="form-control" value="<?php echo e(old('zip_code')); ?>">
                        <?php $__errorArgs = ['zip_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- Availability Section -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Delivery Availabilities</h4>
                            <button type="button" class="btn btn-sm btn-success float-right" id="add-availability">
                                Add Availability
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="availabilities-container">
                                <!-- Availability rows will be added here -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group text-center">
                        <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm"><?php echo e(__('messages.Submit')); ?></button>
                        <a href="<?php echo e(route('deliveries.index')); ?>" class="btn btn-sm btn-danger"><?php echo e(__('messages.Cancel')); ?></a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Availability Row Template -->
<div id="availability-template" style="display: none;">
    <div class="availability-row card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Day of Week</label>
                        <select name="availabilities[INDEX][day_of_week]" class="form-control" required>
                            <option value="">Select Day</option>
                            <?php $__currentLoopData = $daysOfWeek; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>"><?php echo e($day); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Time From</label>
                        <input type="time" name="availabilities[INDEX][time_from]" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Time To</label>
                        <input type="time" name="availabilities[INDEX][time_to]" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Active</label>
                        <div class="form-check">
                            <input type="checkbox" name="availabilities[INDEX][is_active]" class="form-check-input" checked>
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-danger btn-sm form-control remove-availability">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
$(document).ready(function() {
    let availabilityIndex = 0;

    // Add availability row
    $('#add-availability').click(function() {
        let template = $('#availability-template').html();
        template = template.replace(/INDEX/g, availabilityIndex);
        $('#availabilities-container').append(template);
        availabilityIndex++;
    });

    // Remove availability row
    $(document).on('click', '.remove-availability', function() {
        $(this).closest('.availability-row').remove();
    });

    // Add first availability row on page load
    $('#add-availability').click();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\mercaso\resources\views/admin/deliveries/create.blade.php ENDPATH**/ ?>