

<?php $__env->startSection('title', __('messages.Point_Transactions')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><?php echo e(__('messages.Point_Transactions')); ?></h3>
                    <a href="<?php echo e(route('point-transactions.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> <?php echo e(__('messages.Add_New')); ?>

                    </a>
                </div>

                <!-- Filter Form -->
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('point-transactions.index')); ?>" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo e(__('messages.User')); ?></label>
                                    <select name="user_id" class="form-control">
                                        <option value=""><?php echo e(__('messages.All_Users')); ?></option>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($user->id); ?>" <?php echo e(request('user_id') == $user->id ? 'selected' : ''); ?>>
                                                <?php echo e($user->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo e(__('messages.Transaction_Type')); ?></label>
                                    <select name="type_of_transaction" class="form-control">
                                        <option value=""><?php echo e(__('messages.All_Types')); ?></option>
                                        <option value="1" <?php echo e(request('type_of_transaction') == '1' ? 'selected' : ''); ?>>
                                            <?php echo e(__('messages.Add_Points')); ?>

                                        </option>
                                        <option value="2" <?php echo e(request('type_of_transaction') == '2' ? 'selected' : ''); ?>>
                                            <?php echo e(__('messages.Withdraw_Points')); ?>

                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label><?php echo e(__('messages.Date_From')); ?></label>
                                    <input type="date" name="date_from" class="form-control" value="<?php echo e(request('date_from')); ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label><?php echo e(__('messages.Date_To')); ?></label>
                                    <input type="date" name="date_to" class="form-control" value="<?php echo e(request('date_to')); ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-info">
                                            <i class="fas fa-search"></i> <?php echo e(__('messages.Search')); ?>

                                        </button>
                                        <a href="<?php echo e(route('point-transactions.index')); ?>" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> <?php echo e(__('messages.Clear')); ?>

                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Results Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('messages.ID')); ?></th>
                                    <th><?php echo e(__('messages.User')); ?></th>
                                    <th><?php echo e(__('messages.Admin')); ?></th>
                                    <th><?php echo e(__('messages.Points')); ?></th>
                                    <th><?php echo e(__('messages.Type')); ?></th>
                                    <th><?php echo e(__('messages.Note')); ?></th>
                                    <th><?php echo e(__('messages.Date')); ?></th>
                                    <th><?php echo e(__('messages.Actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $pointTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($transaction->id); ?></td>
                                        <td>
                                            <?php if($transaction->user): ?>
                                                <span class="badge badge-info"><?php echo e($transaction->user->name); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted"><?php echo e(__('messages.Unknown_User')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($transaction->admin): ?>
                                                <?php echo e($transaction->admin->name); ?>

                                            <?php else: ?>
                                                <span class="text-muted"><?php echo e(__('messages.System')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?php echo e($transaction->type_of_transaction == 1 ? 'success' : 'danger'); ?>">
                                                <?php echo e($transaction->type_of_transaction == 1 ? '+' : '-'); ?><?php echo e($transaction->points); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <?php if($transaction->type_of_transaction == 1): ?>
                                                <span class="badge badge-success"><?php echo e(__('messages.Add_Points')); ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-danger"><?php echo e(__('messages.Withdraw_Points')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($transaction->note): ?>
                                                <span title="<?php echo e($transaction->note); ?>">
                                                    <?php echo e(Str::limit($transaction->note, 50)); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted"><?php echo e(__('messages.No_Note')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($transaction->created_at->format('Y-m-d H:i')); ?></td>
                                        <td>
                                            <div class="btn-group">
                                              
                                                <a href="<?php echo e(route('point-transactions.edit', $transaction)); ?>" 
                                                   class="btn btn-sm btn-warning" title="<?php echo e(__('messages.Edit')); ?>">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="<?php echo e(route('point-transactions.destroy', $transaction)); ?>" 
                                                      style="display: inline-block;"
                                                      onsubmit="return confirm('<?php echo e(__('messages.Are_you_sure')); ?>')">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-danger" title="<?php echo e(__('messages.Delete')); ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">
                                            <?php echo e(__('messages.No_point_transactions_found')); ?>

                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        <?php echo e($pointTransactions->appends(request()->query())->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\mercaso\resources\views/admin/pointTransactions/index.blade.php ENDPATH**/ ?>