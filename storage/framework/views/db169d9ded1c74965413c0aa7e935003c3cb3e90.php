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

                    <!-- Country Selection -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="country"><?php echo e(__('messages.countries')); ?></label>
                            <select class="form-control" name="country" id="country">
                                <option value="">Select countries</option>
                                <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($country->id); ?>" <?php echo e($country->id == $data->country_id ? 'selected' : ''); ?>>
                                        <?php echo e($country->name_ar); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['country'];
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

                    <!-- Representative Selection -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="representative"><?php echo e(__('messages.representatives')); ?></label>
                            <select class="form-control" name="representative" id="representative">
                                <option value="">Select representatives</option>
                                <?php $__currentLoopData = $representatives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $representative): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($representative->id); ?>" <?php echo e($representative->id == $data->representative_id ? 'selected' : ''); ?>>
                                        <?php echo e($representative->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['representative'];
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

                    <!-- Wholesale Information -->
                    <?php if($data->wholeSales): ?>
                        <div class="col-md-6">
                            <div class="image">
                                <?php if($data->wholeSales->first()->store_license ?? null): ?>
                                    <img class="custom_img" src="<?php echo e(asset('assets/admin/uploads/' . $data->wholeSales->first()->store_license)); ?>">
                                <?php else: ?>
                                    <p>No store license available.</p>
                                <?php endif; ?>
                            </div>
                            <div class="image">
                                <?php if($data->wholeSales->first()->commercial_record ?? null): ?>
                                    <img class="custom_img" src="<?php echo e(asset('assets/admin/uploads/' . $data->wholeSales->first()->commercial_record)); ?>">
                                <?php else: ?>
                                    <p>No commercial record available.</p>
                                <?php endif; ?>
                            </div>
                            <div class="image">
                                <?php if($data->wholeSales->first()->import_license ?? null): ?>
                                    <img class="custom_img" src="<?php echo e(asset('assets/admin/uploads/' . $data->wholeSales->first()->import_license)); ?>">
                                <?php else: ?>
                                    <p>No import license available.</p>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="company_type">Company Type</label>
                                <p><?php echo e($data->wholeSales->first()->company_type ?? 'N/A'); ?></p>

                                <label for="other_company_type">Other Company Type</label>
                                <p><?php echo e($data->wholeSales->first()->other_company_type ?? 'N/A'); ?></p>

                                <label for="tax_number">Tax Number</label>
                                <p><?php echo e($data->wholeSales->first()->tax_number ?? 'N/A'); ?></p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="col-md-6">
                            <p>No wholesale information available.</p>
                        </div>
                    <?php endif; ?>

                    <!-- Address -->
                    <div class="col-md-6">
                        <label for="location">Location:</label>
                        <p><?php echo e($data->addresses->first()->address ?? 'N/A'); ?></p>
                    </div>

                    <!-- Payment Option -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo e(__('messages.can_pay_with_receivable')); ?></label>
                            <select name="can_pay_with_receivable" id="can_pay_with_receivable" class="form-control">
                                <option value="">Select</option>
                                <option value="1" <?php echo e($data->can_pay_with_receivable == 1 ? 'selected' : ''); ?>>Yes</option>
                                <option value="2" <?php echo e($data->can_pay_with_receivable == 2 ? 'selected' : ''); ?>>No</option>
                            </select>
                            <?php $__errorArgs = ['can_pay_with_receivable'];
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u573862697/domains/vertex-jordan.online/public_html/resources/views/admin/wholeSales/edit.blade.php ENDPATH**/ ?>