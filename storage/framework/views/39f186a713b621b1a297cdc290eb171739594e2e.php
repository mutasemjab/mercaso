<?php $__env->startSection('title'); ?>
<?php echo e(__('messages.products')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
    /* Style for the "plus" button */
    #add-variation {
        display: block;
        margin-top: 10px;
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
    }

    /* Style for the variation fields container */
    #variationFields {
        border: 1px solid #ccc;
        padding: 10px;
        margin-top: 10px;
    }

    /* Style for individual variation fields */
    .variation {
        border: 1px solid #ccc;
        padding: 10px;
        margin-top: 10px;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> <?php echo e(__('messages.Add_New')); ?> <?php echo e(__('messages.products')); ?></h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <form action="<?php echo e(route('products.store')); ?>" method="post" enctype='multipart/form-data'>
            <div class="row">
                <?php echo csrf_field(); ?>
                <div class="form-group col-md-6">
                    <label for="category_id"> <?php echo e(__('messages.categories')); ?></label>
                    <select class="form-control" name="category" id="category_id">
                        <option value="">Select Parent Category</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category->id); ?>"><?php echo e($category->name_en); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['category'];
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
                    <label for="unit_id"> <?php echo e(__('messages.unit_for_user')); ?></label>
                    <select class="form-control" name="unit" id="unit_id">
                        <option value="">Select Unit</option>
                        <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($unit->id); ?>"><?php echo e($unit->name_en); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['unit'];
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
                    <label for="number"> <?php echo e(__('messages.number')); ?></label>
                    <input name="number" id="number" class="form-control" value="<?php echo e(old('number')); ?>">
                    <?php $__errorArgs = ['number'];
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
                    <label for="barcode"> <?php echo e(__('messages.barcode')); ?></label>
                    <input name="barcode" id="barcode" class="form-control" value="<?php echo e(old('barcode')); ?>">
                    <?php $__errorArgs = ['barcode'];
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
                    <label for="name_ar"> <?php echo e(__('messages.Name_ar')); ?></label>
                    <input name="name_ar" id="name_ar" class="form-control" value="<?php echo e(old('name_ar')); ?>">
                    <?php $__errorArgs = ['name_ar'];
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
                    <label for="name_en"> <?php echo e(__('messages.Name_en')); ?></label>
                    <input name="name_en" id="name_en" class="form-control" value="<?php echo e(old('name_en')); ?>">
                    <?php $__errorArgs = ['name_en'];
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
                    <label for="name_fr"> <?php echo e(__('messages.name_fr')); ?></label>
                    <input name="name_fr" id="name_fr" class="form-control" value="<?php echo e(old('name_fr')); ?>">
                    <?php $__errorArgs = ['name_fr'];
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
                    <label for="description_en"> <?php echo e(__('messages.description_en')); ?></label>
                    <textarea name="description_en" id="description_en" class="form-control" rows="8"><?php echo e(old('description_en')); ?></textarea>
                    <?php $__errorArgs = ['description_en'];
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
                    <label for="description_ar"> <?php echo e(__('messages.description_ar')); ?></label>
                    <textarea name="description_ar" id="description_ar" class="form-control" rows="8"><?php echo e(old('description_ar')); ?></textarea>
                    <?php $__errorArgs = ['description_ar'];
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
                    <label for="description_fr"> <?php echo e(__('messages.description_fr')); ?></label>
                    <textarea name="description_fr" id="description_fr" class="form-control" rows="8"><?php echo e(old('description_fr')); ?></textarea>
                    <?php $__errorArgs = ['description_fr'];
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
                    <label for="tax"> <?php echo e(__('messages.tax')); ?> %</label>
                    <input name="tax" id="tax" class="form-control" value="<?php echo e(old('tax')); ?>">
                    <?php $__errorArgs = ['tax'];
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
                    <label for="selling_price_for_user"> <?php echo e(__('messages.selling_price_for_user')); ?></label>
                    <input name="selling_price_for_user" id="selling_price_for_user" class="form-control" value="<?php echo e(old('selling_price_for_user')); ?>">
                    <?php $__errorArgs = ['selling_price_for_user'];
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
                    <label for="min_order_for_user"> <?php echo e(__('messages.min_order_for_user')); ?></label>
                    <input name="min_order_for_user" id="min_order_for_user" class="form-control" value="<?php echo e(old('min_order_for_user')); ?>">
                    <?php $__errorArgs = ['min_order_for_user'];
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
                    <label for="min_order_for_wholesale"> <?php echo e(__('messages.min_order_for_wholesale')); ?></label>
                    <input name="min_order_for_wholesale" id="min_order_for_wholesale" class="form-control" value="<?php echo e(old('min_order_for_wholesale')); ?>">
                    <?php $__errorArgs = ['min_order_for_wholesale'];
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
                    <label for="status"> <?php echo e(__('messages.Status')); ?></label>
                    <select name="status" id="status" class="form-control">
                        <option value="">Select</option>
                        <option <?php if(old('status')==1 || old('status')==""): ?> selected="selected" <?php endif; ?> value="1">Active</option>
                        <option <?php if(old('status')==2 and old('status')!=""): ?> selected="selected" <?php endif; ?> value="2">Inactive</option>
                    </select>
                    <?php $__errorArgs = ['status'];
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
                    <label for="in_stock"> <?php echo e(__('messages.in_stock')); ?></label>
                    <select name="in_stock" id="in_stock" class="form-control">
                        <option value="">Select</option>
                        <option <?php if(old('in_stock')==1 || old('in_stock')==""): ?> selected="selected" <?php endif; ?> value="1">Yes</option>
                        <option <?php if(old('in_stock')==2 and old('in_stock')!=""): ?> selected="selected" <?php endif; ?> value="2">No</option>
                    </select>
                    <?php $__errorArgs = ['in_stock'];
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
                    <label for="has_variation"> <?php echo e(__('messages.has_variation')); ?></label>
                    <select name="has_variation" id="has_variation" class="form-control">
                        <option value="">Select</option>
                        <option <?php if(old('has_variation')==1 || old('has_variation')==""): ?> selected="selected" <?php endif; ?> value="1">Active</option>
                        <option <?php if(old('has_variation')==0 and old('has_variation')!=""): ?> selected="selected" <?php endif; ?> value="0">Inactive</option>
                    </select>
                    <?php $__errorArgs = ['has_variation'];
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

                <div id="variationFields" class="form-group col-md-12">
                    <div class="variation row">
                        <div class="form-group col-md-4">
                            <input type="text" name="attributes[]" class="form-control" placeholder="Attributes">
                        </div>
                        <div class="form-group col-md-4">
                            <input type="text" name="variations[]" class="form-control" placeholder="Variations">
                        </div>
                        <div class="form-group col-md-4">
                            <input type="number" name="available_quantities[]" class="form-control" placeholder="Available Quantity">
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <button type="button" id="add-variation" class="btn btn-primary">Add Variation</button>
                </div>

                <div class="form-group col-md-12">
                    <img src="" id="image-preview" alt="Selected Image" height="50px" width="50px" style="display: none;">
                    <button class="btn"> Photo</button>
                    <input type="file" id="Item_img" name="photo[]" class="form-control" onchange="previewImage()" multiple>
                    <?php $__errorArgs = ['photo'];
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

            <ul class="nav nav-tabs" id="productTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tab1-tab" data-bs-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true"><?php echo e(__('messages.another_units')); ?></a>
                </li>
            </ul>

            <div class="tab-content" id="productTabContent">
                <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                    <div id="product-units-container" class="mt-3">
                        <div class="row product-unit">
                            <div class="form-group col-md-3">
                                <label for="unit"><?php echo e(__('messages.unit_for_wholeSale')); ?></label>
                                <select name="units[]" class="form-control" required>
                                    <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($unit->id); ?>"><?php echo e($unit->name_en); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="barcode"><?php echo e(__('messages.barcode')); ?></label>
                                <input type="number" class="form-control" name="barcodes[]">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="releation"><?php echo e(__('messages.releation')); ?></label>
                                <input type="number" class="form-control" name="releations[]">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="selling_price"><?php echo e(__('messages.selling_price')); ?></label>
                                <input type="number" class="form-control" name="selling_prices[]" step="0.01" min="0">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary mt-3" id="add-unit">Add Unit</button>
                </div>
            </div>

            <div class="form-group col-md-12 text-center">
                <button id="do_add_item_cardd" type="submit" class="btn btn-primary"><?php echo e(__('messages.Submit')); ?></button>
                <a href="<?php echo e(route('products.index')); ?>" class="btn btn-danger"><?php echo e(__('messages.Cancel')); ?></a>
            </div>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    // Function to toggle visibility of variation fields
    function toggleVariationFields() {
        const hasVariation = document.getElementById('has_variation').value;
        const variationFields = document.getElementById('variationFields');

        if (hasVariation === '1') {
            variationFields.style.display = 'block';
        } else {
            variationFields.style.display = 'none';
        }
    }

    // Initial state on page load
    toggleVariationFields();

    // Event listener to toggle fields when the selection changes
    document.getElementById('has_variation').addEventListener('change', toggleVariationFields);

    // Function to add new variation fields
    document.getElementById('add-variation').addEventListener('click', function () {
        const variationFields = document.getElementById('variationFields');
        const variation = document.querySelector('.variation');
        const clone = variation.cloneNode(true);
        variationFields.appendChild(clone);
    });

    $(document).ready(function() {
        $('#productTab a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        $('#add-unit').on('click', function() {
            const unitTemplate = `
                <div class="row product-unit">
                    <div class="form-group col-md-3">
                        <label for="unit"><?php echo e(__('messages.unit')); ?></label>
                        <select name="units[]" class="form-control" required>
                            <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($unit->id); ?>"><?php echo e($unit->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="barcode"><?php echo e(__('messages.barcode')); ?></label>
                        <input type="number" class="form-control" name="barcodes[]">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="releation"><?php echo e(__('messages.releation')); ?></label>
                        <input type="number" class="form-control" name="releations[]">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="selling_price"><?php echo e(__('messages.selling_price')); ?></label>
                        <input type="number" class="form-control" name="selling_prices[]">
                    </div>
                </div>`;
            $('#product-units-container').append(unitTemplate);
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u573862697/domains/vertex-jordan.online/public_html/resources/views/admin/products/create.blade.php ENDPATH**/ ?>