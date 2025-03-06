<?php $__env->startSection('title'); ?>

<?php echo e(__('messages.Edit')); ?> <?php echo e(__('messages.products')); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('contentheaderlink'); ?>
<a href="<?php echo e(route('products.index')); ?>"> <?php echo e(__('messages.products')); ?> </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contentheaderactive'); ?>
<?php echo e(__('messages.Edit')); ?>

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
        <h3 class="card-title card_title_center"> <?php echo e(__('messages.Edit')); ?> <?php echo e(__('messages.products')); ?> </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">


        <form action="<?php echo e(route('products.update',$data['id'])); ?>" method="post" enctype='multipart/form-data'>
            <div class="row">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>



                <div class="form-group col-md-6">
                    <label for="category_id">Parent Category</label>
                    <select class="form-control" name="category" id="category_id">
                        <option value="">Select Parent Category</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category->id); ?>" <?php echo e($category->id == $data->category_id ? 'selected' : ''); ?>>
                            <?php echo e($category->name); ?>

                        </option>
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
                    <label for="unit_id"><?php echo e(__('messages.unit_for_user')); ?></label>
                    <select class="form-control" name="unit" id="unit_id">
                        <option value="">Select Unit</option>
                        <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($unit->id); ?>" <?php echo e($unit->id == $data->unit_id ? 'selected' : ''); ?>>
                            <?php echo e($unit->name); ?>

                        </option>
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

                <div class="col-md-6">
                    <div class="form-group">
                        <label> <?php echo e(__('messages.number')); ?></label>
                        <input name="number" id="number" class="form-control"
                            value="<?php echo e(old('number', $data->number)); ?>">
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
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> <?php echo e(__('messages.barcode')); ?></label>
                        <input name="barcode" id="barcode" class="form-control"
                            value="<?php echo e(old('barcode', $data->barcode)); ?>">
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
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> <?php echo e(__('messages.Name_ar')); ?></label>
                        <input name="name_ar" id="name_ar" class="form-control"
                            value="<?php echo e(old('name_ar', $data->name_ar)); ?>">
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
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> <?php echo e(__('messages.Name_en')); ?></label>
                        <input name="name_en" id="name_en" class="form-control"
                            value="<?php echo e(old('name_en', $data->name_en)); ?>">
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
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> <?php echo e(__('messages.Name_fr')); ?></label>
                        <input name="name_fr" id="name_fr" class="form-control"
                            value="<?php echo e(old('name_fr', $data->name_fr)); ?>">
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
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> <?php echo e(__('messages.description_en')); ?></label>
                        <textarea name="description_en" id="description_en" class="form-control"
                            value="<?php echo e(old('description_en')); ?>" rows="8"><?php echo e($data->description_en); ?></textarea>
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
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label> <?php echo e(__('messages.description_ar')); ?></label>
                        <textarea name="description_ar" id="description_ar" class="form-control"
                            value="<?php echo e(old('description_ar')); ?>" rows="8"><?php echo e($data->description_ar); ?></textarea>
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
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label> <?php echo e(__('messages.description_fr')); ?></label>
                        <textarea name="description_fr" id="description_fr" class="form-control"
                            value="<?php echo e(old('description_fr')); ?>" rows="8"><?php echo e($data->description_fr); ?></textarea>
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
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo e(__('messages.tax')); ?> %</label>
                        <input name="tax" id="tax" class="form-control" value="<?php echo e(old('tax', $data->tax)); ?>">
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
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo e(__('messages.selling_price_for_user')); ?></label>
                        <input name="selling_price_for_user" id="selling_price_for_user" class="form-control"
                            value="<?php echo e(old('selling_price_for_user', $data->selling_price_for_user)); ?>">
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
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo e(__('messages.min_order_for_user')); ?></label>
                        <input name="min_order_for_user" id="min_order_for_user" class="form-control"
                            value="<?php echo e(old('min_order_for_user', $data->min_order_for_user)); ?>">
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
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo e(__('messages.min_order_for_wholesale')); ?></label>
                        <input name="min_order_for_wholesale" id="min_order_for_wholesale" class="form-control"
                            value="<?php echo e(old('min_order_for_wholesale', $data->min_order_for_wholesale)); ?>">
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
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> <?php echo e(__('messages.Status')); ?></label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Select</option>
                            <option value="1" <?php echo e($data->status == 1 ? 'selected' : ''); ?>>Active</option>
                            <option value="2" <?php echo e($data->status == 2 ? 'selected' : ''); ?>>Inactive</option>
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
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> <?php echo e(__('messages.in_stock')); ?></label>
                        <select name="in_stock" id="in_stock" class="form-control">
                            <option value="">Select</option>
                            <option value="1" <?php echo e($data->in_stock == 1 ? 'selected' : ''); ?>>Yes</option>
                            <option value="2" <?php echo e($data->in_stock == 2 ? 'selected' : ''); ?>>No</option>
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
                </div>



                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo e(__('messages.has_variation')); ?></label>
                        <select name="has_variation" id="has_variation" class="form-control">
                            <option value="1" <?php echo e($data->has_variation == 1 ? 'selected' : ''); ?>>Active</option>
                            <option value="0" <?php echo e($data->has_variation == 0 ? 'selected' : ''); ?>>Inactive</option>
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
                </div>

                <div id="variationFields">
                    <?php if($data->has_variation): ?>
                    <?php $__currentLoopData = $data->variations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="variation">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="attributes[]" placeholder="Attributes"
                                    value="<?php echo e($variation->attributes); ?>">
                                <br>
                                <br>
                                <input type="text" name="variations[]" placeholder="Variations"
                                    value="<?php echo e($variation->variation); ?>">
                                <br>
                                <br>
                                <input type="number" name="available_quantities[]" placeholder="Available Quantity"
                                    value="<?php echo e($variation->available_quantity); ?>">
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <button type="button" id="add-variation">Add Variation</button>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <?php if($data->productImages->count() > 0): ?>
                        <?php $__currentLoopData = $data->productImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <img src="<?php echo e(asset('assets/admin/uploads/' . $image->photo)); ?>" alt="Product Image"
                            height="50px" width="50px">
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <p>No images available for this product.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Product Images</label>
                        <input type="file" name="photo[]" class="form-control" multiple>
                    </div>
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
            <?php $__currentLoopData = $data->units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productUnit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="row product-unit">
                    <!-- Unit Selection -->
                    <div class="form-group col-md-3">
                        <label for="unit"><?php echo e(__('messages.unit_for_wholeSale')); ?></label>
                        <select name="units[]" class="form-control" required>
                            <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($unit->id); ?>" <?php echo e($productUnit->id == $unit->id ? 'selected' : ''); ?>><?php echo e($unit->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Barcode Input -->
                    <div class="form-group col-md-3">
                        <label for="barcode"><?php echo e(__('messages.barcode')); ?></label>
                        <input type="number" class="form-control" name="barcodes[]" value="<?php echo e($productUnit->pivot->barcode); ?>">
                    </div>

                    <!-- Releation Input -->
                    <div class="form-group col-md-3">
                        <label for="releation"><?php echo e(__('messages.releation')); ?></label>
                        <input type="number" class="form-control" name="releations[]" value="<?php echo e($productUnit->pivot->releation); ?>">
                    </div>

                    <!-- Selling Price Input -->
                    <div class="form-group col-md-3">
                        <label for="selling_price"><?php echo e(__('messages.selling_price')); ?></label>
                        <input type="number" class="form-control" name="selling_prices[]" value="<?php echo e($productUnit->pivot->selling_price); ?>" step="any">
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <button type="button" class="btn btn-secondary mt-3" id="add-unit">Add Unit</button>
    </div>
</div>

            <div class="col-md-12 text-center">
                <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm">Update</button>
                <a href="<?php echo e(route('products.index')); ?>" class="btn btn-sm btn-danger">Cancel</a>
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
        $('#productTab a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        $('#add-unit').on('click', function() {
            const unitTemplate = `
                <div class="row product-unit">
                    <div class="col-md-3">
                        <div class="form-group mt-3">
                            <label for="unit"><?php echo e(__('messages.unit')); ?></label>
                            <select name="units[]" class="form-control" required>
                                <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($unit->id); ?>"><?php echo e($unit->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mt-3">
                            <label for="barcode"><?php echo e(__('messages.barcode')); ?></label>
                            <input type="number" class="form-control" name="barcodes[]">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mt-3">
                            <label for="releation"><?php echo e(__('messages.releation')); ?></label>
                            <input type="number" class="form-control" name="releations[]">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="selling_price"><?php echo e(__('messages.selling_price')); ?></label>
                            <input type="number" class="form-control" name="selling_prices[]" step="any">
                        </div>
                    </div>
                </div>`;
            $('#product-units-container').append(unitTemplate);
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\vertex\resources\views/admin/products/edit.blade.php ENDPATH**/ ?>