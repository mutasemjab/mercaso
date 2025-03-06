<?php $__env->startSection('title'); ?>
    <?php echo e(__('messages.wholeSales')); ?>

<?php $__env->stopSection(); ?>





<?php $__env->startSection('content'); ?>



    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> <?php echo e(__('messages.wholeSales')); ?> </h3>
            <input type="hidden" id="token_search" value="<?php echo e(csrf_token()); ?>">
            <input type="hidden" id="ajax_search_url" value="<?php echo e(route('admin.wholeSale.ajax_search')); ?>">
            <a href="<?php echo e(route('admin.wholeSale.import.show')); ?>" class="btn btn-sm btn-success">
                <?php echo e(__('messages.wholeSalesImport')); ?></a>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


            <form method="get" action="<?php echo e(route('admin.wholeSale.index')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="row my-3">
                    <div class="col-md-6 ">
                        <input autofocus type="text" placeholder="" name="search" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-success "> <?php echo e(__('messages.Search')); ?> </button>
                    </div>
                </div>
            </form>


            <div class="clearfix"></div>

            <div id="ajax_responce_serarchDiv" class="col-md-12">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('wholeSale-table')): ?>
                    <?php if(@isset($data) && !@empty($data) && count($data) > 0): ?>
                        <table id="example2" class="table table-bordered table-hover">
                            <thead class="custom_thead">

                                <th><?php echo e(__('messages.Name')); ?>  </th>
                                <th> <?php echo e(__('messages.Email')); ?>  </th>
                                <th><?php echo e(__('messages.Phone')); ?>  </th>
                                <th><?php echo e(__('messages.activate')); ?>  </th>
                                <th></th>

                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>

                                        <td><?php echo e($info->name); ?></td>
                                        <td><?php echo e($info->email); ?></td>
                                        <td><?php echo e($info->phone); ?></td>
                                        <td><?php echo e($info->activate == 1 ? 'Active' : 'InActive'); ?></td>




                                        <td>
                                            
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('wholeSale-edit')): ?>
                                                <a href="<?php echo e(route('admin.wholeSale.edit', $info->id)); ?>"
                                                    class="btn btn-sm  btn-primary"> <?php echo e(__('messages.Edit')); ?></a>
                                            <?php endif; ?>
                                            <a href="<?php echo e(route('admin.wholeSale.export', ['search' => $searchQuery])); ?>"
                                                class="btn btn-sm btn-success">Export to Excel</a>

                                        </td>


                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



                            </tbody>
                        </table>
                        <br>
                        <?php echo e($data->appends(['search' => $searchQuery])->links()); ?>

                    <?php else: ?>
                        <div class="alert alert-danger">
                            <?php echo e(__('messages.No_data')); ?> </div>
                    <?php endif; ?>

                </div>
            <?php endif; ?>

        </div>

    </div>

    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/admin/js/wholeSales.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u573862697/domains/vertex-jordan.online/public_html/resources/views/admin/wholeSales/index.blade.php ENDPATH**/ ?>