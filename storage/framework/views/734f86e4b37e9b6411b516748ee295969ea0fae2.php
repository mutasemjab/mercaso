<?php $__env->startSection('title'); ?>
<?php echo e(__('messages.orders')); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('contentheaderactive'); ?>
<?php echo e(__('messages.Show')); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>



    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> <?php echo e(__('messages.orders')); ?>

            </h3>
            <input type="hidden" id="token_search" value="<?php echo e(csrf_token()); ?>">

            <a href="<?php echo e(route('orders.create')); ?>" class="btn btn-sm btn-success"> <?php echo e(__('messages.New')); ?>

                <?php echo e(__('messages.orders')); ?></a>

                <form action="<?php echo e(route('orders.index')); ?>" method="GET" class="form-inline float-right">
                    <input type="text" name="search" class="form-control mr-sm-2" placeholder="<?php echo e(__('messages.Search')); ?>">
                    <button type="submit" class="btn btn-primary"><?php echo e(__('messages.Search')); ?></button>
                </form>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">

                    

                    

                </div>

            </div>
            <div class="clearfix"></div>

            <div id="ajax_responce_serarchDiv" class="col-md-12">

                <?php if(isset($data) && !$data->isEmpty()): ?>
                    <table id="example2" class="table table-bordered table-hover">
                        <thead class="custom_thead">
                            <th>#<?php echo e(__('messages.ID')); ?></th>
                            <th><?php echo e(__('messages.Date')); ?></th>
                            <th><?php echo e(__('messages.order_type')); ?></th>
                            <th><?php echo e(__('messages.order_status')); ?></th>
                            <th><?php echo e(__('messages.delivery_fee')); ?></th>
                            <th><?php echo e(__('messages.total_prices')); ?></th>
                            <th><?php echo e(__('messages.total_discount')); ?></th>
                            <th><?php echo e(__('messages.payment_type')); ?></th>
                            <th><?php echo e(__('messages.payment_status')); ?></th>
                            <th><?php echo e(__('messages.Action')); ?></th>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>

                                    <td><?php echo e($info->number); ?></td>
                                    <td><?php echo e($info->date); ?></td>
                                    <td style="<?php echo e($info->order_type == 2 ? 'color:red;' : ''); ?>">
                                        <?php echo e($info->order_type == 1 ? __('Sell') : __('Refund')); ?>

                                    </td>

                                    <td>

                                        <?php if($info->order_status == 1): ?>
                                        <?php echo e(__('messages.Pending')); ?>

                                        <?php elseif($info->order_status == 2): ?>
                                        <?php echo e(__('messages.Accepted')); ?>

                                        <?php elseif($info->order_status == 3): ?>
                                        <?php echo e(__('messages.OnTheWay')); ?>

                                        <?php elseif($info->order_status == 4): ?>
                                        <?php echo e(__('messages.Delivered')); ?>

                                        <?php elseif($info->order_status == 5): ?>
                                        <?php echo e(__('messages.Canceled')); ?>

                                        <?php else: ?>
                                        <?php echo e(__('messages.Refund')); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($info->delivery_fee); ?></td>
                                    <td><?php echo e($info->total_prices); ?></td>
                                    <td><?php echo e($info->total_discounts); ?></td>
                                    <td><?php echo e($info->payment_type == 1 ?  'cash' : 'visa'); ?></td>
                                    <td>
                                        <?php if($info->payment_status == 1): ?>
                                            Paid
                                        <?php else: ?>
                                            UnPaid
                                        <?php endif; ?>
                                    </td>


                                    <td>
                                        <a href="<?php echo e(route('orders.edit', $info->id)); ?>"
                                            class="btn btn-sm btn-primary"> <?php echo e(__('messages.Edit')); ?></a>
                                        <a href="<?php echo e(route('orders.show', $info->id)); ?>"
                                            class="btn btn-sm btn-primary"> <?php echo e(__('messages.Show')); ?></a>
                                 <!--       <form action="<?php echo e(route('orders.destroy', $info->id)); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger"> <?php echo e(__('messages.Delete')); ?></button>
                                        </form>-->
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <br>
                    <?php echo e($data->links()); ?>

                <?php else: ?>
                    <div class="alert alert-danger">
                        <?php echo e(__('messages.no_data')); ?>

                    </div>
                <?php endif; ?>

            </div>



        </div>

    </div>

    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/admin/js/orderss.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u573862697/domains/vertex-jordan.online/public_html/resources/views/admin/orders/index.blade.php ENDPATH**/ ?>