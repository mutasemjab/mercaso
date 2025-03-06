<?php $__env->startSection('title'); ?>
<?php echo e(__('messages.Edit')); ?> <?php echo e(__('messages.noteVoucherTypes')); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('contentheaderlink'); ?>
<a href="<?php echo e(route('noteVoucherTypes.index')); ?>"> <?php echo e(__('messages.noteVoucherTypes')); ?> </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contentheaderactive'); ?>
<?php echo e(__('messages.Edit')); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> <?php echo e(__('messages.Edit')); ?> <?php echo e(__('messages.noteVoucherTypes')); ?> </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">


        <form action="<?php echo e(route('noteVoucherTypes.update', $data['id'])); ?>" method="post" enctype='multipart/form-data'>
            <div class="row">
                <?php echo csrf_field(); ?>


                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo e(__('messages.Name')); ?></label>
                        <input name="name" id="name" class="" value="<?php echo e(old('name', $data['name'])); ?>">
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
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo e(__('messages.Name_en')); ?></label>
                        <input name="name_en" id="name_en" class="" value="<?php echo e(old('name_en', $data['name_en'])); ?>">
                        <?php $__errorArgs = ['name_en'];
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
                        <label><?php echo e(__('messages.in_out_type')); ?></label>
                        <select name="in_out_type" id="in_out_type" class="form-control">
                            <option value="">Select</option>
                            <option <?php if($data->in_out_type == 1): ?> selected="selected" <?php endif; ?> value="1">ادخال</option>
                            <option <?php if($data->in_out_type == 2): ?> selected="selected" <?php endif; ?> value="2">اخراج</option>
                            <option <?php if($data->in_out_type == 3): ?> selected="selected" <?php endif; ?> value="3">نقل</option>
                        </select>
                        <?php $__errorArgs = ['in_out_type'];
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
                        <label><?php echo e(__('messages.have_price')); ?></label>
                        <select name="have_price" id="have_price" class="form-control">
                            <option value="">Select</option>
                            <option <?php if($data->have_price == 1): ?> selected="selected" <?php endif; ?> value="1">Yes</option>
                            <option <?php if($data->have_price == 2): ?> selected="selected" <?php endif; ?> value="2">No</option>
                        </select>
                        <?php $__errorArgs = ['have_price'];
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
                        <label> <?php echo e(__('messages.header')); ?></label>
                        <textarea name="header" id="header" class="form-control" value="<?php echo e(old('header')); ?>" rows="12"><?php echo e($data['header']); ?></textarea>
                        <?php $__errorArgs = ['header'];
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
                        <label> <?php echo e(__('messages.footer')); ?></label>
                        <textarea name="footer" id="footer" class="form-control" value="<?php echo e(old('footer')); ?>" rows="12"><?php echo e($data['footer']); ?></textarea>
                        <?php $__errorArgs = ['footer'];
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






                <div class="col-md-12">
                    <div class="form-group text-center">
                        <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm">
                            <?php echo e(__('messages.Update')); ?></button>
                        <a href="<?php echo e(route('noteVoucherTypes.index')); ?>" class="btn btn-sm btn-danger"><?php echo e(__('messages.Cancel')); ?></a>

                    </div>
                </div>

            </div>
        </form>



    </div>




</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u573862697/domains/vertex-jordan.online/public_html/resources/views/admin/noteVoucherTypes/edit.blade.php ENDPATH**/ ?>