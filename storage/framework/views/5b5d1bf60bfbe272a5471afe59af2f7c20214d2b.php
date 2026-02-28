<?php $__env->startSection('title', 'Products'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products Management</h1>
        <div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('product-add')): ?>
                <a href="<?php echo e(route('products.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Product
                </a>
            <?php endif; ?>
        </div>
    </div>


    <!-- Search and Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="<?php echo e(route('products.index')); ?>" method="GET" class="form-inline">
                <div class="form-group mr-2">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Search by name, number, or barcode..." 
                           value="<?php echo e(request('search')); ?>"
                           style="min-width: 300px;">
                </div>
                <button type="submit" class="btn btn-primary mr-2">
                    <i class="fas fa-search"></i> Search
                </button>
                <?php if(request('search')): ?>
                    <a href="<?php echo e(route('products.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Products Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Products List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="10%">Image</th>
                            <th width="15%">Name</th>
                            <th width="10%">Category</th>
                            <th width="8%">Type</th>
                            <th width="8%">Price</th>
                            <th width="8%">Stock</th>
                            <th width="8%">Status</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($data->firstItem() + $index); ?></td>
                                <td>
                                    <?php if($product->productImages->count() > 0): ?>
                                        <img src="<?php echo e(asset('assets/admin/uploads/'.$product->productImages->first()->photo)); ?>" 
                                             alt="<?php echo e($product->name_ar); ?>" 
                                             class="img-thumbnail" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    <?php else: ?>
                                        <img src="<?php echo e(asset('assets/admin/img/no-image.png')); ?>" 
                                             alt="No Image" 
                                             class="img-thumbnail" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    <?php endif; ?>
                                </td>
                           
                                <td>
                                    <strong><?php echo e($product->name_ar); ?></strong>
                                    <br>
                                    <small class="text-muted"><?php echo e($product->name_en); ?></small>
                                </td>
                                <td>
                                    <?php if($product->category): ?>
                                        <span class="badge badge-info"><?php echo e($product->category->name_ar); ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($product->product_type == 1): ?>
                                        <span class="badge badge-primary">Retail</span>
                                    <?php elseif($product->product_type == 2): ?>
                                        <span class="badge badge-warning">Wholesale</span>
                                    <?php else: ?>
                                        <span class="badge badge-success">Both</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($product->product_type == 1 || $product->product_type == 3): ?>
                                        <strong>$ <?php echo e(number_format($product->selling_price_for_user, 2)); ?></strong>
                                    <?php else: ?>
                                        <span class="text-muted">Wholesale Only</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($product->in_stock == 1): ?>
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i> In Stock
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times"></i> Out of Stock
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" 
                                               class="custom-control-input status-toggle" 
                                               id="status-<?php echo e($product->id); ?>" 
                                               data-id="<?php echo e($product->id); ?>"
                                               <?php echo e($product->status == 1 ? 'checked' : ''); ?>>
                                        <label class="custom-control-label" for="status-<?php echo e($product->id); ?>">
                                            <span class="status-text-<?php echo e($product->id); ?>">
                                                <?php echo e($product->status == 1 ? 'Active' : 'Inactive'); ?>

                                            </span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('product-edit')): ?>
                                            <a href="<?php echo e(route('products.edit', ['product' => $product->id, 'page' => $data->currentPage()])); ?>" 
                                               class="btn btn-sm btn-warning" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('product-delete')): ?>
                                            <form action="<?php echo e(route('products.destroy', $product->id)); ?>" 
                                                  method="POST" 
                                                  class="d-inline delete-form">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" 
                                                        class="btn btn-sm btn-danger" 
                                                        title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this product?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No products found.</p>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('product-add')): ?>
                                        <a href="<?php echo e(route('products.create')); ?>" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Add Your First Product
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if($data->hasPages()): ?>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        Showing <?php echo e($data->firstItem()); ?> to <?php echo e($data->lastItem()); ?> of <?php echo e($data->total()); ?> products
                    </div>
                    <div>
                        <?php echo e($data->appends(request()->query())->links()); ?>

                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Status toggle functionality
    $('.status-toggle').on('change', function() {
        const productId = $(this).data('id');
        const isChecked = $(this).is(':checked');
        const toggleSwitch = $(this);
        
        $.ajax({
           url: '<?php echo e(route("products.toggleStatus", ":id")); ?>'.replace(':id', productId),
            type: 'PATCH',
            data: {
                _token: '<?php echo e(csrf_token()); ?>'
            },
            success: function(response) {
                if(response.success) {
                    // Update status text
                    $(`.status-text-${productId}`).text(response.status_text);
   
                } else {
                    // Revert toggle if failed
                    toggleSwitch.prop('checked', !isChecked);
                }
            },
            error: function() {
                // Revert toggle on error
                toggleSwitch.prop('checked', !isChecked);
            }
        });
    });
    
});
</script>
<?php $__env->stopPush(); ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u167651649/domains/mutasemjaber.online/public_html/mercaso/resources/views/admin/products/index.blade.php ENDPATH**/ ?>