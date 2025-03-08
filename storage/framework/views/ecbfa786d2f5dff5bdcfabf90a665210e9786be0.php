<?php $__env->startSection('title'); ?>
    <?php echo e(__('messages.offers')); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> <?php echo e(__('messages.New')); ?><?php echo e(__('messages.offers')); ?> </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


            <form action="<?php echo e(route('offers.store')); ?>" method="post" enctype='multipart/form-data'>
                <div class="row">
                    <?php echo csrf_field(); ?>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_type"><?php echo e(__('messages.user_type')); ?></label>
                            <select name="user_type" class="form-control" required>
                                <option value="1"><?php echo e(__('messages.User')); ?></option>
                                <option value="2"><?php echo e(__('messages.wholeSales')); ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> <?php echo e(__('messages.Price')); ?></label>
                            <input name="price" id="price" class="form-control" value="<?php echo e(old('price')); ?>">
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

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> <?php echo e(__('messages.start_at')); ?></label>
                            <input type="date" name="start_at" id="price" class="form-control"
                                value="<?php echo e(old('start_at')); ?>">
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
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> <?php echo e(__('messages.end_at')); ?></label>
                            <input type="date" name="expired_at" id="price" class="form-control"
                                value="<?php echo e(old('expired_at')); ?>">
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
                    </div>



                    <div class="form-group col-md-6">
                        <label for="product_id"><?php echo e(__('messages.products')); ?></label>
                        <select class="form-control" name="product" id="product_id">
                            <option value="">Select Product</option>
                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($product->id); ?>"><?php echo e($product->name_ar); ?></option>
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
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo e(__('messages.selling_price')); ?></label>
                            <div id="selling_price_display">N/A</div> <!-- Display area for the selling price -->
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?php echo e(__('messages.selling_price_for_user')); ?></label>
                            <div id="selling_price_for_user_display">N/A</div> <!-- Display area for selling_price_for_user -->
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm">
                                <?php echo e(__('messages.Submit')); ?></button>
                            <a href="<?php echo e(route('offers.index')); ?>"
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
<script>
    $(document).ready(function() {
        $('#product_id').select2({
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

        // Event listener for when a product is selected
        $('#product_id').on('select2:select', function (e) {
            var productId = e.params.data.id; // Get selected product ID

            // AJAX request to get selling prices for the selected product
            $.ajax({
                url: '/products/get-prices/' + productId, // Create a new route for this purpose
                method: 'GET',
                success: function(response) {
                    if (response.selling_price) {
                        $('#selling_price_display').text(response.selling_price); // Display the selling price
                    } else {
                        $('#selling_price_display').text('N/A'); // Default if no selling price is found
                    }

                    if (response.selling_price_for_user) {
                        $('#selling_price_for_user_display').text(response.selling_price_for_user); // Display selling_price_for_user
                    } else {
                        $('#selling_price_for_user_display').text('N/A'); // Default if no selling_price_for_user is found
                    }
                },
                error: function() {
                    $('#selling_price_display').text('N/A'); // Handle error
                    $('#selling_price_for_user_display').text('N/A'); // Handle error
                }
            });
        });

        // Initialize the display with N/A
        $('#selling_price_display').text('N/A');
        $('#selling_price_for_user_display').text('N/A');
    });
</script>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\mercaso\resources\views/admin/offers/create.blade.php ENDPATH**/ ?>