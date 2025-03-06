<?php $__env->startSection('title'); ?>
    <?php echo e(__('messages.Edit')); ?> <?php echo e(__('messages.warehouses')); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('contentheaderlink'); ?>
    <a href="<?php echo e(route('warehouses.index')); ?>"> <?php echo e(__('messages.warehouses')); ?> </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contentheaderactive'); ?>
    <?php echo e(__('messages.Edit')); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> <?php echo e(__('messages.Edit')); ?> <?php echo e(__('messages.warehouses')); ?> </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


            <form action="<?php echo e(route('warehouses.update', $data['id'])); ?>" method="post" enctype='multipart/form-data'>
                <div class="row">
                    <?php echo csrf_field(); ?>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo e(__('messages.Name')); ?></label>
                            <input name="name" id="name" class=""
                                value="<?php echo e(old('name', $data['name'])); ?>">
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
                            <label for="shop"><?php echo e(__('messages.shops')); ?></label>
                            <select class="form-control" name="shop" id="shop_id">
                                <option value="">Select shops</option>
                                <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($shop->id); ?>" <?php if($data->shop_id == $shop->id): ?> selected <?php endif; ?>><?php echo e($shop->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <option value="0" <?php if($data->shop_id === null): ?> selected <?php endif; ?>>No shop</option>
                            </select>
                            <?php $__errorArgs = ['shop'];
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
                            <a href="<?php echo e(route('warehouses.index')); ?>"
                                class="btn btn-sm btn-danger"><?php echo e(__('messages.Cancel')); ?></a>

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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u573862697/domains/vertex-jordan.online/public_html/resources/views/admin/warehouses/edit.blade.php ENDPATH**/ ?>