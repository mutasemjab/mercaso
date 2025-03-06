<?php $__env->startSection('title'); ?>
    <?php echo e(__('messages.products')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('contentheaderactive'); ?>
    <?php echo e(__('messages.show')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> <?php echo e(__('messages.products')); ?></h3>

        <a href="<?php echo e(route('products.import.show')); ?>" class="btn btn-sm btn-success">
            <?php echo e(__('messages.productsImport')); ?>

        </a>
        <a href="<?php echo e(route('products.create')); ?>" class="btn btn-sm btn-success"> <?php echo e(__('messages.New')); ?>

            <?php echo e(__('messages.products')); ?>

        </a>

        <!-- Search Form -->
        <form action="<?php echo e(route('products.index')); ?>" method="GET" class="form-inline float-right">
            <input type="text" name="search" class="form-control mr-sm-2" placeholder="<?php echo e(__('messages.Search')); ?>">
            <button type="submit" class="btn btn-primary"><?php echo e(__('messages.Search')); ?></button>
        </form>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-4"></div>
        </div>
        <div class="clearfix"></div>
        <div id="ajax_responce_serarchDiv" class="col-md-12">
            <?php if(isset($data) && !$data->isEmpty()): ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('product-table')): ?>
                    <table id="example2" class="table table-bordered table-hover">
                        <thead class="custom_thead">
                            <th><?php echo e(__('messages.Name')); ?></th>
                            <th><?php echo e(__('messages.Categories')); ?></th>
                            <th><?php echo e(__('messages.Status')); ?></th>
                            <th><?php echo e(__('messages.Photo')); ?></th>
                            <th><?php echo e(__('messages.Action')); ?></th>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($info->name_ar); ?></td>
                                    <td>
                                        <?php if($info->category): ?>
                                            <a href="<?php echo e(route('categories.index', ['id' => $info->category->id])); ?>"><?php echo e($info->category->name_ar); ?></a>
                                        <?php else: ?>
                                            No Category
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($info->status == 1 ? 'Active' : 'Not Active'); ?></td>
                                    <td>
                                        <?php if($info->productImages->isNotEmpty()): ?>
                                            <div class="image">
                                                <img class="custom_img" src="<?php echo e(asset('assets/admin/uploads/' . $info->productImages->first()->photo)); ?>" alt="Product Image">
                                            </div>
                                        <?php else: ?>
                                            No Photo
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('product-edit')): ?>
                                            <a href="<?php echo e(route('products.edit', $info->id)); ?>" class="btn btn-sm btn-primary"><?php echo e(__('messages.Edit')); ?></a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('product-delete')): ?>
                                            <form action="<?php echo e(route('products.destroy', $info->id)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-danger"><?php echo e(__('messages.Delete')); ?></button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                <?php endif; ?>
                <br>
                <?php echo e($data->links()); ?>

            <?php else: ?>
                <div class="alert alert-danger"><?php echo e(__('messages.No_data')); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\vertex\resources\views/admin/products/index.blade.php ENDPATH**/ ?>