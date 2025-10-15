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
                                <th><?php echo e(__('messages.Price')); ?></th>
                                <th><?php echo e(__('messages.barcode')); ?></th>
                                <th><?php echo e(__('messages.Number')); ?></th>
                                <th><?php echo e(__('messages.Categories')); ?></th>

                                <th><?php echo e(__('messages.Photo')); ?></th>
                                <th><?php echo e(__('messages.Status')); ?></th>
                                <th><?php echo e(__('messages.Action')); ?></th>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($info->name_ar); ?></td>
                                        <td><?php echo e($info->selling_price_for_user); ?></td>
                                        <td><?php echo e($info->barcode); ?></td>
                                        <td><?php echo e($info->number); ?></td>
                                        <td>
                                            <?php if($info->category): ?>
                                                <a
                                                    href="<?php echo e(route('categories.index', ['id' => $info->category->id])); ?>"><?php echo e($info->category->name_ar); ?></a>
                                            <?php else: ?>
                                                No Category
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php if($info->productImages->isNotEmpty()): ?>
                                                <div class="image">
                                                    <img class="custom_img"
                                                        src="<?php echo e(asset('assets/admin/uploads/' . $info->productImages->first()->photo)); ?>"
                                                        alt="Product Image">
                                                </div>
                                            <?php else: ?>
                                                No Photo
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('product-edit')): ?>
                                                <label class="switch">
                                                    <input type="checkbox" class="status-toggle" data-id="<?php echo e($info->id); ?>"
                                                        <?php echo e($info->status == 1 ? 'checked' : ''); ?>>
                                                    <span class="slider round"></span>
                                                </label>
                                            <?php else: ?>
                                                <span class="badge badge-<?php echo e($info->status == 1 ? 'success' : 'danger'); ?>">
                                                    <?php echo e($info->status == 1 ? 'Active' : 'Not Active'); ?>

                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('product-edit')): ?>
                                                <a href="<?php echo e(route('products.edit', $info->id)); ?>"
                                                    class="btn btn-sm btn-primary"><?php echo e(__('messages.Edit')); ?></a>
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
    <style>
        /* Toggle Switch Styles */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #28a745;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #28a745;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        /* Alert styles for notifications */
        .alert-fixed {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        }
    </style>

    <script>
        $(document).ready(function() {
            // Function to show notifications
            function showNotification(message, type = 'success') {
                var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                var alertHtml = '<div class="alert ' + alertClass +
                    ' alert-fixed alert-dismissible fade show" role="alert">' +
                    message +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span>' +
                    '</button>' +
                    '</div>';

                $('body').append(alertHtml);

                // Auto dismiss after 3 seconds
                setTimeout(function() {
                    $('.alert-fixed').fadeOut();
                }, 3000);
            }

            $('.status-toggle').change(function() {
                var productId = $(this).data('id');
                var isChecked = $(this).is(':checked');
                var toggle = $(this);

                // Disable the toggle while processing
                toggle.prop('disabled', true);

                // Get the correct URL based on your route structure
                var url = '<?php echo e(route('products.toggleStatus', ':id')); ?>';
                url = url.replace(':id', productId);

                $.ajax({
                    url: url,
                    type: 'PATCH',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            showNotification(response.message, 'success');
                        } else {
                            // Revert the toggle if there was an error
                            toggle.prop('checked', !isChecked);
                            showNotification(response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        console.log('Error:', xhr);
                        // Revert the toggle if there was an error
                        toggle.prop('checked', !isChecked);
                        showNotification('An error occurred while updating the status',
                        'error');
                    },
                    complete: function() {
                        // Re-enable the toggle
                        toggle.prop('disabled', false);
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\mercaso\resources\views/admin/products/index.blade.php ENDPATH**/ ?>