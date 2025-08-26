<?php $__env->startSection('title'); ?>
<?php echo e(__('messages.Edit')); ?> <?php echo e(__('messages.products')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('contentheaderlink'); ?>
<a href="<?php echo e(route('products.index')); ?>"> <?php echo e(__('messages.products')); ?> </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contentheaderactive'); ?>
<?php echo e(__('messages.Edit')); ?>

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

                <!-- Product Type Field -->
                <div class="form-group col-md-6">
                    <label for="product_type"> <?php echo e(__('messages.product_type')); ?></label>
                    <select class="form-control" name="product_type" id="product_type">
                        <option value="3" <?php echo e(($data->product_type ?? 3) == 3 ? 'selected' : ''); ?>>Both (Retail & Wholesale)</option>
                        <option value="1" <?php echo e(($data->product_type ?? 3) == 1 ? 'selected' : ''); ?>>Retail Only</option>
                        <option value="2" <?php echo e(($data->product_type ?? 3) == 2 ? 'selected' : ''); ?>>Wholesale Only</option>
                    </select>
                    <?php $__errorArgs = ['product_type'];
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
                    <label for="brand_id">Brand</label>
                    <select class="form-control" name="brand" id="brand_id">
                        <option value="">Select Brand</option>
                        <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($brand->id); ?>" <?php echo e($brand->id == $data->brand_id ? 'selected' : ''); ?>>
                            <?php echo e($brand->name); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['brand'];
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
                    <label for="category_id">Parent Category</label>
                    <select class="form-control" name="category" id="category_id">
                        <option value="">Select Parent Category</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category->id); ?>" <?php echo e($category->id == $data->category_id ? 'selected' : ''); ?>>
                            <?php echo e($category->name_en); ?>

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

                <div class="form-group col-md-6">
                    <label for="number"> <?php echo e(__('messages.number')); ?></label>
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

                <div class="form-group col-md-6">
                    <label for="barcode"> <?php echo e(__('messages.barcode')); ?></label>
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

                <div class="form-group col-md-6">
                    <label for="points"> <?php echo e(__('messages.points')); ?></label>
                    <input name="points" id="points" class="form-control"
                        value="<?php echo e(old('points', $data->points)); ?>">
                    <?php $__errorArgs = ['points'];
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

                <div class="form-group col-md-6">
                    <label for="name_en"> <?php echo e(__('messages.Name_en')); ?></label>
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

                <div class="form-group col-md-6">
                    <label for="description_en"> <?php echo e(__('messages.description_en')); ?></label>
                    <textarea name="description_en" id="description_en" class="form-control" rows="8"><?php echo e(old('description_en', $data->description_en)); ?></textarea>
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
                    <textarea name="description_ar" id="description_ar" class="form-control" rows="8"><?php echo e(old('description_ar', $data->description_ar)); ?></textarea>
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

                <!-- Modified Tax Section -->
                <?php
                    $hasTax = $data->tax ? '1' : '0';
                    $selectedTaxId = null;
                    if ($data->tax) {
                        foreach($taxes as $tax) {
                            if ($tax->value == $data->tax) {
                                $selectedTaxId = $tax->id;
                                break;
                            }
                        }
                    }
                ?>

                <div class="form-group col-md-6">
                    <label for="has_tax"><?php echo e(__('messages.has_tax')); ?></label>
                    <select name="has_tax" id="has_tax" class="form-control">
                        <option value="0" <?php echo e($hasTax == '0' ? 'selected' : ''); ?>>No</option>
                        <option value="1" <?php echo e($hasTax == '1' ? 'selected' : ''); ?>>Yes</option>
                    </select>
                    <?php $__errorArgs = ['has_tax'];
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

                <div class="form-group col-md-6" id="tax_dropdown" style="<?php echo e($hasTax == '1' ? 'display: block;' : 'display: none;'); ?>">
                    <label for="tax_id"><?php echo e(__('messages.select_tax')); ?></label>
                    <select name="tax_id" id="tax_id" class="form-control">
                        <option value="">Select Tax</option>
                        <?php $__currentLoopData = $taxes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tax): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($tax->id); ?>" <?php echo e($selectedTaxId == $tax->id ? 'selected' : ''); ?>>
                            <?php echo e($tax->name); ?> (<?php echo e($tax->value); ?>%)
                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['tax_id'];
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

                <!-- Modified CRV Section -->
                <?php
                    $hasCrv = $data->crv ? '1' : '0';
                    $selectedCrvId = null;
                    if ($data->crv) {
                        foreach($crvs as $crv) {
                            if ($crv->value == $data->crv) {
                                $selectedCrvId = $crv->id;
                                break;
                            }
                        }
                    }
                ?>

                <div class="form-group col-md-6">
                    <label for="has_crv"><?php echo e(__('messages.has_crv')); ?></label>
                    <select name="has_crv" id="has_crv" class="form-control">
                        <option value="0" <?php echo e($hasCrv == '0' ? 'selected' : ''); ?>>No</option>
                        <option value="1" <?php echo e($hasCrv == '1' ? 'selected' : ''); ?>>Yes</option>
                    </select>
                    <?php $__errorArgs = ['has_crv'];
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

                <div class="form-group col-md-6" id="crv_dropdown" style="<?php echo e($hasCrv == '1' ? 'display: block;' : 'display: none;'); ?>">
                    <label for="crv_id"><?php echo e(__('messages.select_crv')); ?></label>
                    <select name="crv_id" id="crv_id" class="form-control">
                        <option value="">Select CRV</option>
                        <?php $__currentLoopData = $crvs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $crv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($crv->id); ?>" <?php echo e($selectedCrvId == $crv->id ? 'selected' : ''); ?>>
                            <?php echo e($crv->name); ?> (<?php echo e($crv->value); ?>)
                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['crv_id'];
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

                <!-- Retail Fields - Hidden/Shown based on product type -->
                <div class="form-group col-md-6" id="retail_price_field">
                    <label for="selling_price_for_user"><?php echo e(__('messages.selling_price_for_user')); ?></label>
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

                <div class="form-group col-md-6" id="retail_min_order_field">
                    <label for="min_order_for_user"><?php echo e(__('messages.min_order_for_user')); ?></label>
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

                <!-- Wholesale Fields - Hidden/Shown based on product type -->
                <div class="form-group col-md-6" id="wholesale_min_order_field">
                    <label for="min_order_for_wholesale"><?php echo e(__('messages.min_order_for_wholesale')); ?></label>
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

                <div class="form-group col-md-6">
                    <label for="status"> <?php echo e(__('messages.Status')); ?></label>
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

                <div class="form-group col-md-6">
                    <label for="in_stock"> <?php echo e(__('messages.in_stock')); ?></label>
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

                <div class="form-group col-md-6">
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

                <div class="form-group col-md-6">
                    <label>Product Images</label>
                    <input type="file" name="photo[]" class="form-control" multiple>
                </div>

            </div>

            <!-- Wholesale Units Tab - Only show if product type is wholesale or both -->
            <div id="wholesale_units_section">
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
                                        <select name="units[]" class="form-control">
                                            <option value="">Select Unit</option>
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
            </div>

            <div class="form-group col-md-12 text-center">
                <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm">Update</button>
                <a href="<?php echo e(route('products.index')); ?>" class="btn btn-sm btn-danger">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    // Function to toggle fields based on product type
    function toggleProductTypeFields() {
        const productType = document.getElementById('product_type').value;
        const retailPriceField = document.getElementById('retail_price_field');
        const retailMinOrderField = document.getElementById('retail_min_order_field');
        const wholesaleMinOrderField = document.getElementById('wholesale_min_order_field');
        const wholesaleUnitsSection = document.getElementById('wholesale_units_section');

        // Clear required attributes first
        document.getElementById('selling_price_for_user').removeAttribute('required');
        document.getElementById('min_order_for_user').removeAttribute('required');
        document.getElementById('min_order_for_wholesale').removeAttribute('required');

        if (productType === '1') { // Retail only
            retailPriceField.style.display = 'block';
            retailMinOrderField.style.display = 'block';
            wholesaleMinOrderField.style.display = 'none';
            wholesaleUnitsSection.style.display = 'none';
            
            // Set required for retail fields
            document.getElementById('selling_price_for_user').setAttribute('required', 'required');
            document.getElementById('min_order_for_user').setAttribute('required', 'required');
        } else if (productType === '2') { // Wholesale only
            retailPriceField.style.display = 'none';
            retailMinOrderField.style.display = 'none';
            wholesaleMinOrderField.style.display = 'block';
            wholesaleUnitsSection.style.display = 'block';
            
            // Set required for wholesale fields
            document.getElementById('min_order_for_wholesale').setAttribute('required', 'required');
        } else { // Both (3)
            retailPriceField.style.display = 'block';
            retailMinOrderField.style.display = 'block';
            wholesaleMinOrderField.style.display = 'block';
            wholesaleUnitsSection.style.display = 'block';
            
            // Set required for all fields
            document.getElementById('selling_price_for_user').setAttribute('required', 'required');
            document.getElementById('min_order_for_user').setAttribute('required', 'required');
            document.getElementById('min_order_for_wholesale').setAttribute('required', 'required');
        }
    }

    // Function to toggle tax dropdown
    function toggleTaxDropdown() {
        const hasTaxElement = document.getElementById('has_tax');
        const taxDropdown = document.getElementById('tax_dropdown');

        if (hasTaxElement && taxDropdown) {
            if (hasTaxElement.value === '1') {
                taxDropdown.style.display = 'block';
            } else {
                taxDropdown.style.display = 'none';
            }
        }
    }

    // Function to toggle crv dropdown
    function toggleCrvDropdown() {
        const hasCrvElement = document.getElementById('has_crv');
        const crvDropdown = document.getElementById('crv_dropdown');

        if (hasCrvElement && crvDropdown) {
            if (hasCrvElement.value === '1') {
                crvDropdown.style.display = 'block';
            } else {
                crvDropdown.style.display = 'none';
            }
        }
    }

    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Initial state on page load
        toggleProductTypeFields();

        // Event listeners
        const productTypeElement = document.getElementById('product_type');
        const hasTaxElement = document.getElementById('has_tax');
        const hasCrvElement = document.getElementById('has_crv');

        if (productTypeElement) {
            productTypeElement.addEventListener('change', toggleProductTypeFields);
        }
        
        if (hasTaxElement) {
            hasTaxElement.addEventListener('change', toggleTaxDropdown);
        }
        
        if (hasCrvElement) {
            hasCrvElement.addEventListener('change', toggleCrvDropdown);
        }
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
                        <select name="units[]" class="form-control">
                            <option value="">Select Unit</option>
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
                        <input type="number" class="form-control" name="selling_prices[]" step="any">
                    </div>
                </div>`;
            $('#product-units-container').append(unitTemplate);
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\mercaso\resources\views/admin/products/edit.blade.php ENDPATH**/ ?>