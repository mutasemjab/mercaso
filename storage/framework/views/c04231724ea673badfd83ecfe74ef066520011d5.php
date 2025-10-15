<?php $__env->startSection('title'); ?>
    <?php echo e(__('messages.Edit')); ?> <?php echo e(__('messages.wholeSales')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('contentheaderlink'); ?>
    <a href="<?php echo e(route('admin.wholeSale.index')); ?>"> <?php echo e(__('messages.wholeSales')); ?> </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contentheaderactive'); ?>
    <?php echo e(__('messages.Edit')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"><?php echo e(__('messages.Edit')); ?> <?php echo e(__('messages.wholeSales')); ?></h3>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('admin.wholeSale.update', $data['id'])); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <!-- Name Field -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo e(__('messages.Name')); ?></label>
                            <input name="name" id="name" class="form-control" value="<?php echo e(old('name', $data['name'])); ?>">
                            <?php $__errorArgs = ['name'];
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

                    <!-- Email Field -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo e(__('messages.Email')); ?></label>
                            <input name="email" id="email" class="form-control" value="<?php echo e(old('email', $data['email'])); ?>">
                            <?php $__errorArgs = ['email'];
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

                    <!-- Phone Field -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo e(__('messages.Phone')); ?></label>
                            <input name="phone" id="phone" class="form-control" value="<?php echo e(old('phone', $data['phone'])); ?>">
                            <?php $__errorArgs = ['phone'];
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

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo e(__('messages.Password')); ?></label>
                            <input name="password" id="password" class="form-control" value="">
                            <?php $__errorArgs = ['password'];
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



                    <!-- Photo Upload -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="photo">Photo</label>
                            <input type="file" name="photo" id="photo" class="form-control-file">
                            <?php if($data->photo): ?>
                                <img src="<?php echo e(asset('assets/admin/uploads/' . $data->photo)); ?>" id="image-preview" alt="Selected Image" height="50px" width="50px">
                            <?php else: ?>
                                <img src="" id="image-preview" alt="Selected Image" height="50px" width="50px" style="display: none;">
                            <?php endif; ?>
                            <?php $__errorArgs = ['photo'];
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


                    <!-- Activation Status -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo e(__('messages.Activate')); ?></label>
                            <select name="activate" id="activate" class="form-control">
                                <option value="">Select</option>
                                <option value="1" <?php echo e($data->activate == 1 ? 'selected' : ''); ?>>Active</option>
                                <option value="2" <?php echo e($data->activate == 2 ? 'selected' : ''); ?>>Inactive</option>
                            </select>
                            <?php $__errorArgs = ['activate'];
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

                    <!-- Submit Button -->
                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm"><?php echo e(__('messages.Update')); ?></button>
                            <a href="<?php echo e(route('admin.wholeSale.index')); ?>" class="btn btn-sm btn-danger"><?php echo e(__('messages.Cancel')); ?></a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/admin/js/wholeSales.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\mercaso\resources\views/admin/wholeSales/edit.blade.php ENDPATH**/ ?>