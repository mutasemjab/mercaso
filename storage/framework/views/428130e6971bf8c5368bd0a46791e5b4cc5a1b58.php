<?php $__env->startSection('title'); ?>
    edit Delivery
<?php $__env->stopSection(); ?>



<?php $__env->startSection('contentheaderlink'); ?>
    <a href="<?php echo e(route('deliveries.index')); ?>"> Delivery </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contentheaderactive'); ?>
    Edit
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> edit Delivery</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


            <form action="<?php echo e(route('deliveries.update', $data['id'])); ?>" method="post" enctype='multipart/form-data'>
                <div class="row">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label> Place Name</label>
                            <input name="place" id="place" class="form-control"
                                value="<?php echo e(old('place', $data['place'])); ?>">
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label> Price</label>
                            <input name="price" id="price" class="form-control"
                                value="<?php echo e(old('price', $data['price'])); ?>">
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
                    


                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm"> update</button>
                            <a href="<?php echo e(route('deliveries.index')); ?>" class="btn btn-sm btn-danger">cancel</a>

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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u573862697/domains/vertex-jordan.online/public_html/resources/views/admin/deliveries/edit.blade.php ENDPATH**/ ?>