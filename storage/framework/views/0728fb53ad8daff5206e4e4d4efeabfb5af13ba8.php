
<?php $__env->startSection('title'); ?>
<?php echo e(__('messages.Edit')); ?>  <?php echo e(__('messages.offers')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('contentheaderlink'); ?>
<a href="<?php echo e(route('offers.index')); ?>">  <?php echo e(__('messages.offers')); ?>  </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contentheaderactive'); ?>
<?php echo e(__('messages.Edit')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> <?php echo e(__('messages.Edit')); ?> <?php echo e(__('messages.offers')); ?> </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">

        <form action="<?php echo e(route('offers.update', $data['id'])); ?>" method="post" enctype='multipart/form-data'>
            <div class="row">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="user_type"><?php echo e(__('messages.user_type')); ?></label>
                        <select name="user_type" id="user_type" class="form-control" required>
                            <option value="1" <?php echo e($data->user_type == 1 ? 'selected' : ''); ?>><?php echo e(__('messages.User')); ?></option>
                            <option value="2" <?php echo e($data->user_type == 2 ? 'selected' : ''); ?>><?php echo e(__('messages.wholeSales')); ?></option>
                        </select>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="category_header">Select Product</label>
                    <select class="form-control" name="product" id="category_header">
                        <option value="">Select Product</option>
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($product->id); ?>" <?php if($data->product_id == $product->id): ?> selected <?php endif; ?>><?php echo e($product->name_ar); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['product'];
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

                <div class="form-group col-md-6">
                    <label for="name"><?php echo e(__('messages.price')); ?> <span class="text-danger">*</span></label>
                    <input type="text" name="price" id="name" class="form-control" value="<?php echo e($data->price); ?>">
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

                <div class="form-group col-md-6">
                    <label for="name"><?php echo e(__('messages.start_at')); ?> <span class="text-danger">*</span></label>
                    <input type="date" name="start_at" id="name" class="form-control" value="<?php echo e($data->start_at); ?>">
                    <?php $__errorArgs = ['start_at'];
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

                <div class="form-group col-md-6">
                    <label for="name"> <?php echo e(__('messages.end_at')); ?> <span class="text-danger">*</span></label>
                    <input type="date" name="expired_at" id="name" class="form-control" value="<?php echo e($data->expired_at); ?>">
                    <?php $__errorArgs = ['expired_at'];
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

                <div class="form-group col-md-6">
                    <label><?php echo e(__('messages.selling_price')); ?></label>
                    <div id="selling_price_display"><?php echo e($data->selling_price ?? 'N/A'); ?></div> <!-- Display area for selling price -->
                </div>
                
                <div class="form-group col-md-6">
                    <label><?php echo e(__('messages.selling_price_for_user')); ?></label>
                    <div id="selling_price_for_user_display"><?php echo e($data->selling_price_for_user ?? 'N/A'); ?></div> <!-- Display area for selling_price_for_user -->
                </div>

                <div class="col-md-12">
                    <div class="form-group text-center">
                        <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm"><?php echo e(__('messages.Update')); ?> </button>
                        <a href="<?php echo e(route('offers.index')); ?>" class="btn btn-sm btn-danger"><?php echo e(__('messages.cancel')); ?></a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('#category_header').select2({
            placeholder: 'Select Product',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '<?php echo e(route('products.search')); ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        term: params.term // search term
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.name,
                            };
                        })
                    };
                },
                cache: true
            }
        });

        // Function to fetch and display prices
        function fetchPrices(productId) {
            $.ajax({
                url: '/products/get-prices/' + productId, // Existing route
                method: 'GET',
                success: function(response) {
                    $('#selling_price_display').text(response.selling_price ? response.selling_price : 'N/A');
                    $('#selling_price_for_user_display').text(response.selling_price_for_user ? response.selling_price_for_user : 'N/A');
                },
                error: function() {
                    $('#selling_price_display').text('N/A');
                    $('#selling_price_for_user_display').text('N/A');
                }
            });
        }

        // Fetch prices when a new product is selected
        $('#category_header').on('select2:select', function (e) {
            var productId = e.params.data.id; // Get selected product ID
            fetchPrices(productId);
        });

        // Fetch prices for the initially selected product
        var initialProductId = $('#category_header').val();
        if (initialProductId) {
            fetchPrices(initialProductId);
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u573862697/domains/vertex-jordan.online/public_html/resources/views/admin/offers/edit.blade.php ENDPATH**/ ?>