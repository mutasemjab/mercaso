<?php $__env->startSection('title', __('messages.role_management')); ?>

<?php $__env->startSection('content'); ?>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?php echo e(__('messages.role_management')); ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('messages.dashboard')); ?></a></li>
                        <li class="breadcrumb-item active"><?php echo e(__('messages.roles')); ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="card-title"><?php echo e(__('messages.roles')); ?></h3>
                                </div>
                                <div class="col-md-6 text-right">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role-add')): ?>
                                        <a href="<?php echo e(route('admin.role.create')); ?>" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> <?php echo e(__('messages.create')); ?>

                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Search Form -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <form method="GET" action="<?php echo e(route('admin.role.index')); ?>">
                                        <div class="input-group">
                                            <input type="text" name="search" class="form-control" 
                                                   placeholder="<?php echo e(__('messages.search')); ?>..." 
                                                   value="<?php echo e(request('search')); ?>">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="submit">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Roles Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th><?php echo e(__('messages.no')); ?></th>
                                            <th><?php echo e(__('messages.name')); ?></th>
                                            <th><?php echo e(__('messages.permissions')); ?></th>
                                            <th><?php echo e(__('messages.created_at')); ?></th>
                                            <th><?php echo e(__('messages.actions')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td><?php echo e($data->firstItem() + $key); ?></td>
                                                <td>
                                                    <strong><?php echo e($role->name); ?></strong>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info">
                                                        <?php echo e($role->permissions->count()); ?> <?php echo e(__('messages.permissions')); ?>

                                                    </span>
                                                </td>
                                                <td><?php echo e($role->created_at->format('Y-m-d H:i')); ?></td>
                                                <td>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role-edit')): ?>
                                                        <a href="<?php echo e(route('admin.role.edit', $role->id)); ?>" 
                                                           class="btn btn-sm btn-info">
                                                            <i class="fas fa-edit"></i> <?php echo e(__('messages.edit')); ?>

                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role-delete')): ?>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-danger delete-btn" 
                                                                data-id="<?php echo e($role->id); ?>">
                                                            <i class="fas fa-trash"></i> <?php echo e(__('messages.delete')); ?>

                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="5" class="text-center"><?php echo e(__('messages.no_data_found')); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="row">
                                <div class="col-sm-12 col-md-5">
                                    <div class="dataTables_info">
                                        <?php echo e(__('messages.showing')); ?> <?php echo e($data->firstItem()); ?> <?php echo e(__('messages.to')); ?> <?php echo e($data->lastItem()); ?> <?php echo e(__('messages.of')); ?> <?php echo e($data->total()); ?> <?php echo e(__('messages.entries')); ?>

                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-7">
                                    <?php echo e($data->appends(request()->query())->links()); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(__('messages.confirm_delete')); ?></h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo e(__('messages.confirm_delete')); ?>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <?php echo e(__('messages.cancel')); ?>

                </button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <?php echo e(__('messages.delete')); ?>

                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        let deleteId = null;
        
        $('.delete-btn').click(function() {
            deleteId = $(this).data('id');
            $('#deleteModal').modal('show');
        });
        
        $('#confirmDelete').click(function() {
            if (deleteId) {
                $.ajax({
                    url: '<?php echo e(route("admin.role.delete")); ?>',
                    type: 'POST',
                    data: {
                        id: deleteId,
                        _token: '<?php echo e(csrf_token()); ?>'
                    },
                    success: function(response) {
                        $('#deleteModal').modal('hide');
                        location.reload();
                    },
                    error: function() {
                        alert('<?php echo e(__("messages.error_occurred")); ?>');
                        $('#deleteModal').modal('hide');
                    }
                });
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u167651649/domains/mutasemjaber.online/public_html/mercaso/resources/views/admin/roles/index.blade.php ENDPATH**/ ?>