<?php $__env->startSection('title'); ?>
<?php echo e(__('messages.product_move')); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-3 text-gray-800"><?php echo e(__('messages.product_move')); ?></h1>
            <form method="GET" action="<?php echo e(route('product_move')); ?>">
                <div class="form-row align-items-end">
                    <div class="form-group col-md-3">
                        <label for="shop_id"><?php echo e(__('messages.shop')); ?></label>
                        <select id="shop_id" name="shop_id" class="form-control" required>
                            <option value=""><?php echo e(__('messages.select_shop')); ?></option>
                            <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($shop->id); ?>" <?php echo e(request('shop_id') == $shop->id ? 'selected' : ''); ?>><?php echo e($shop->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="product_id"><?php echo e(__('messages.products')); ?></label>
                        <select class="form-control" name="product_id" id="product_id">
                            <option value="">Select Product</option>
                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($product->id); ?>" <?php echo e(request('product_id') == $product->id ? 'selected' : ''); ?>><?php echo e($product->name_ar); ?></option>
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

                    <div class="form-group col-md-3">
                        <label for="from_date"><?php echo e(__('messages.from_date')); ?></label>
                        <input type="date" id="from_date" name="from_date" class="form-control" value="<?php echo e(request('from_date', date('Y-m-01'))); ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="to_date"><?php echo e(__('messages.to_date')); ?></label>
                        <input type="date" id="to_date" name="to_date" class="form-control" value="<?php echo e(request('to_date', date('Y-m-t'))); ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <button type="submit" class="btn btn-primary"><?php echo e(__('messages.Show')); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

   <?php if(!empty($reportData)): ?>
    <div class="table-responsive">
        
        <?php if(!empty($reportData['noteVouchers'])): ?>
        <h3>Note Vouchers</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Number</th>
                    <th>Note</th>
                    <th>Date</th>
                    <th>From Warehouse</th>
                    <th>Type</th>
                    <th>Unit</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $reportData['noteVouchers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $voucher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <a href="<?php echo e(route('noteVouchers.edit', $voucher['voucher_id'])); ?>" class="btn btn-link" target="_blank">
                                <?php echo e($voucher['voucher_id']); ?>

                            </a>
                        </td>
                        <td><?php echo e($voucher['note']); ?></td>
                        <td><?php echo e($voucher['date_note_voucher']); ?></td>
                        <td><?php echo e($voucher['from_warehouse']); ?></td>
                        <td>
                            <?php if($voucher['note_voucher_type'] == 1 || $voucher['note_voucher_type'] == 3): ?>
                                In
                            <?php elseif($voucher['note_voucher_type'] == 2): ?>
                                Out
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($voucher['unit_name'] ?? 'N/A'); ?></td>
                        <td><?php echo e($voucher['quantity']); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        
        <h4>Total In: <?php echo e($totalIn); ?></h4>
        <h4>Total Out: <?php echo e($totalOut); ?></h4>
        <h4>Net Quantity: <?php echo e($netQuantity); ?></h4>
        <?php endif; ?>

        
        <?php if(!empty($reportData['orders'])): ?>
        <h3>Orders</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Date</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $reportData['orders']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <a href="<?php echo e(route('orders.edit', $order['order_id'])); ?>" class="btn btn-link" target="_blank">
                                <?php echo e($order['order_number']); ?>

                            </a>
                        </td>
                        <td><?php echo e($order['user_name'] ?? 'N/A'); ?></td>
                        <td><?php echo e($order['date']); ?></td>
                        <td><?php echo e($order['total_prices']); ?></td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Total Price After Tax</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $order['order_products']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orderProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($orderProduct['product_name']); ?></td>
                                            <td><?php echo e($orderProduct['quantity']); ?></td>
                                            <td><?php echo e($orderProduct['unit_price']); ?></td>
                                            <td><?php echo e($orderProduct['total_price_after_tax']); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4"><hr></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
<?php endif; ?>

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
    });
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\vertex\resources\views/reports/product_move.blade.php ENDPATH**/ ?>