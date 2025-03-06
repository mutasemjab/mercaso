

<?php $__env->startSection('content'); ?>
<div class="container">
    <h2>Edit Order</h2>
    <form action="<?php echo e(route('orders.update', $order->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <input type="hidden" name="redirect_to" id="redirect_to" value="index">
       
        <?php if($order->order_status == 6 || $order->order_status == 4): ?>
       <?php else: ?>
        <h6 style="color: red"> Just Click One Click Only </h6>
        <button type="submit" class="btn btn-primary" onclick="setRedirect('index')"><?php echo e(__('messages.Submit')); ?></button>
        <button type="submit" class="btn btn-primary" onclick="setRedirect('show')"><?php echo e(__('messages.Save_Print')); ?></button>
        <?php endif; ?>
      

        <?php if($order->order_status == 6): ?>
        <h3 style="color:red;"><?php echo e(__('messages.Refund')); ?></h3>
        <?php else: ?>
        <div class="col-md-6">
            <div class="form-group mt-3">
                <label for="order_status"><?php echo e(__('messages.order_status')); ?></label>
                <select name="order_status" class="form-control" required>
                    <option value="1" <?php echo e($order->order_status == 1 ? 'selected' : ''); ?>><?php echo e(__('messages.Pending')); ?></option>
                    <option value="2" <?php echo e($order->order_status == 2 ? 'selected' : ''); ?>><?php echo e(__('messages.Accepted')); ?></option>
                    <option value="3" <?php echo e($order->order_status == 3 ? 'selected' : ''); ?>><?php echo e(__('messages.OnTheWay')); ?></option>
                    <option value="4" <?php echo e($order->order_status == 4 ? 'selected' : ''); ?>><?php echo e(__('messages.Delivered')); ?></option>
                    <option value="5" <?php echo e($order->order_status == 5 ? 'selected' : ''); ?>><?php echo e(__('messages.Canceled')); ?></option>
                </select>
            </div>
        </div>
        <?php endif; ?>
        <div class="col-md-6">
            <div class="form-group">
                <label for="date"><?php echo e(__('messages.Date')); ?></label>
                <input type="date" name="date" class="form-control" value="<?php echo e(\Carbon\Carbon::parse($order->date)->format('Y-m-d')); ?>" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mt-3">
                <label for="shop"><?php echo e(__('messages.shops')); ?></label>
                <select name="shop" class="form-control" required>
                    <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($shop->id); ?>" <?php echo e($order->shop_id == $shop->id ? 'selected' : ''); ?>><?php echo e($shop->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mt-3">
                <label for="payment_type"><?php echo e(__('messages.payment_type')); ?></label>
                <select name="payment_type" class="form-control" required>
                    <option value="1" <?php echo e($order->payment_type == 1 ? 'selected' : ''); ?>><?php echo e(__('messages.Cash')); ?></option>
                    <option value="2" <?php echo e($order->payment_type == 2 ? 'selected' : ''); ?>><?php echo e(__('messages.Receivables')); ?></option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mt-3">
                <label for="userSearch"><?php echo e(__('messages.User')); ?></label>
                <input type="text" id="userSearch" class="form-control" name="user" value="<?php echo e($order->user->name); ?>" required />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mt-3">
                <label for="address"><?php echo e(__('messages.Address')); ?></label>
                <select id="addressSelect" class="form-control" name="address" required>
                    <option value=""><?php echo e(__('messages.select_address')); ?></option>
                    <?php $__currentLoopData = $order->user->addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($address->id); ?>" <?php echo e($order->address_id == $address->id ? 'selected' : ''); ?>><?php echo e($address->address); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group mt-3">
                <label for="coupon_discount"><?php echo e(__('messages.Coupon Discount (%)')); ?></label>
                <input type="number" name="coupon_discount" class="form-control" step="0.01" value="<?php echo e($order->coupon_discount); ?>" id="coupon_discount_input" required>
            </div>
        </div>

        <br>
        <table class="table table-bordered" id="products_table">
            <thead>
                <tr>
                    <th><?php echo e(__('messages.product')); ?></th>
                    <th><?php echo e(__('messages.unit')); ?></th>
                    <th><?php echo e(__('messages.quantity')); ?></th>
                    <th><?php echo e(__('messages.selling_price_without_tax')); ?></th>
                    <th><?php echo e(__('messages.selling_price_with_tax')); ?></th>
                    <th><?php echo e(__('messages.tax')); ?></th>
                    <th><?php echo e(__('messages.line_discount_percentage')); ?></th>
                   
                    <th><?php echo e(__('messages.line_discount_fixed')); ?></th>
                    <th><?php echo e(__('messages.total_one_item')); ?></th>
                    <th><?php echo e(__('messages.Action')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $order->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $orderProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><input type="text" class="form-control product-search" name="products[<?php echo e($index); ?>][name]" value="<?php echo e($orderProduct->name_ar); ?>" /></td>
                    <td>
                        <select class="form-control product-unit" name="products[<?php echo e($index); ?>][unit]">
                            <option value="">Select Unit</option>
                            <?php $__currentLoopData = $orderProduct->units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($unit->id); ?>" <?php echo e($orderProduct->pivot->unit_id == $unit->id ? 'selected' : ''); ?>><?php echo e($unit->name_ar); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if($orderProduct->unit): ?>
                                <option value="<?php echo e($orderProduct->unit->id); ?>" <?php echo e($orderProduct->pivot->unit_id == $orderProduct->unit->id ? 'selected' : ''); ?>><?php echo e($orderProduct->unit->name_ar); ?></option>
                            <?php endif; ?>
                        </select>
                    </td>

                    <?php
                    $sellingPriceWithoutTax = round($orderProduct->pivot->unit_price / (1 + ($orderProduct->pivot->tax_percentage / 100)), 3);
                    $sellingPriceWithTax = round($orderProduct->pivot->unit_price, 3);
                    $TaxPercentage = round($orderProduct->pivot->tax_percentage, 3);
                    ?>

                    <td><input type="number" class="form-control quantity" name="products[<?php echo e($index); ?>][quantity]" value="<?php echo e($orderProduct->pivot->quantity); ?>" /></td>
                    <td><input type="number" class="form-control selling_price_without_tax" name="products[<?php echo e($index); ?>][selling_price_without_tax]" step="any" value="<?php echo e($sellingPriceWithoutTax); ?>" /></td>
                    <td><input type="number" class="form-control selling_price_with_tax" name="products[<?php echo e($index); ?>][selling_price_with_tax]" step="any" value="<?php echo e($sellingPriceWithTax); ?>" /></td>
                    <td><input type="number" class="form-control tax" name="products[<?php echo e($index); ?>][tax]" step="any" value="<?php echo e($TaxPercentage); ?>" /></td>
                   
                    <td>
                        <!-- Input for line_discount_percentage (editable by user) -->
                        <input type="number" class="form-control line_discount_percentage" name="products[<?php echo e($index); ?>][line_discount_percentage]" step="any" value="<?php echo e($orderProduct->pivot->line_discount_percentage); ?>" />
                        <!-- Store original percentage discount for backend comparison -->
                        <input type="hidden" name="products[<?php echo e($index); ?>][original_line_discount_percentage]" value="<?php echo e($orderProduct->pivot->line_discount_percentage); ?>" />
                    </td>
                    <td>
                        <!-- Input for line_discount_value (editable by user) -->
                        <input type="number" class="form-control line_discount_value" name="products[<?php echo e($index); ?>][line_discount_value]" step="any" value="<?php echo e($orderProduct->pivot->line_discount_value); ?>" />
                        <!-- Store original line discount value for backend comparison -->
                        <input type="hidden" name="products[<?php echo e($index); ?>][original_line_discount_value]" value="<?php echo e($orderProduct->pivot->line_discount_value); ?>" />
                    </td>
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    <td><input type="number" class="form-control total_one_item" name="products[<?php echo e($index); ?>][total_one_item]" step="any" value="<?php echo e($orderProduct->pivot->total_one_item); ?>" /></td>
                    <td><button type="button" class="btn btn-danger remove-row"><?php echo e(__('messages.Delete')); ?></button></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <button type="button" class="btn btn-primary" id="add_row"><?php echo e(__('messages.Add_Row')); ?></button>

        <!-- Summary Table -->
        <div class="mt-5">
            <h4>Summary</h4>
            <table class="table table-bordered" id="summary_table">
                <tbody>
                    <tr>
                        <td><?php echo e(__('messages.total_selling_price')); ?></td>
                        <td><span id="total_selling_price"><?php echo e($order->total_prices - $order->delivery_fee); ?></span></td>
                    </tr>
                    <tr>
                        <td><?php echo e(__('messages.total_discount')); ?></td>
                        <td><span id="total_discount"><?php echo e($order->total_discounts); ?></span></td>
                    </tr>
                    <tr>
                        <td><?php echo e(__('messages.coupon_discount')); ?></td>
                        <td><span id="coupon_discount"><?php echo e($order->coupon_discount); ?></span></td>
                    </tr>

                    <?php
                    $taxGroups = $order->products->groupBy('pivot.tax_percentage')->map(function ($group) {
                        return $group->sum('pivot.tax_value');
                    });
                    ?>

                    <?php $__currentLoopData = $taxGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $taxPercentage => $taxValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e(__('messages.total_tax')); ?> (<?php echo e($taxPercentage); ?>%)</td>
                        <td><span id="total_tax_<?php echo e($taxPercentage); ?>"><?php echo e(round($taxValue, 3)); ?></span></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <tr>
                        <td><?php echo e(__('messages.delivery_fee')); ?></td>
                        <td><span id="delivery_fee"><?php echo e($order->delivery_fee); ?></span></td>
                    </tr>
                    <tr>
                        <td><?php echo e(__('messages.total_amount')); ?></td>
                        <td><span id="total_amount"><?php echo e($order->total_prices); ?></span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script type="text/javascript">
    function setRedirect(value) {
        document.getElementById('redirect_to').value = value;
    }

    $(document).ready(function() {
        // Initialize user search autocomplete
        $('#userSearch').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: '<?php echo e(route("search.users")); ?>',
                    dataType: 'json',
                    data: {
                        q: request.term
                    },
                    success: function(data) {
                        response($.map(data, function(user) {
                            return {
                                label: user.name,
                                value: user.name,
                                id: user.id
                            };
                        }));
                    }
                });
            },
            minLength: 2,
            select: function(event, ui) {
                $('#userSearch').data('selected-user-id', ui.item.id);
                fetchAddresses(ui.item.id);
            }
        });

        function fetchAddresses(userId) {
            if (!userId) {
                document.getElementById('addressSelect').innerHTML = '<option value=""><?php echo e(__('messages.select_address')); ?></option>';
                return;
            }

            fetch(`<?php echo e(url('user')); ?>/${userId}/addresses`)
                .then(response => response.json())
                .then(data => {
                    const addressSelect = document.getElementById('addressSelect');
                    addressSelect.innerHTML = '<option value=""><?php echo e(__('messages.select_address')); ?></option>';

                    data.forEach(address => {
                        const option = document.createElement('option');
                        option.value = address.id;
                        option.textContent = address.address;
                        addressSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching addresses:', error);
                });
        }

        let rowIdx = <?php echo e($order->products->count()); ?>;

        $('#add_row').on('click', function() {
            $('#products_table tbody').append(`
                <tr>
                    <td><input type="text" class="form-control product-search" name="products[${rowIdx}][name]" /></td>
                    <td>
                        <select class="form-control product-unit" name="products[${rowIdx}][unit]">
                            <option value="">Select Unit</option>
                        </select>
                    </td>
                    <td><input type="number" class="form-control quantity" name="products[${rowIdx}][quantity]" /></td>
                    <td><input type="number" class="form-control selling_price_without_tax" name="products[${rowIdx}][selling_price_without_tax]" step="any" /></td>
                    <td><input type="number" class="form-control selling_price_with_tax" name="products[${rowIdx}][selling_price_with_tax]" step="any"  /></td>
                    <td><input type="number" class="form-control tax" name="products[${rowIdx}][tax]" step="any"  /></td>
                    <td><input type="number" class="form-control line_discount_fixed" name="products[${rowIdx}][line_discount_fixed]}" step="any" /></td>
                    <td><input type="number" class="form-control line_discount_percentage" name="products[${rowIdx}][line_discount_percentage]}" step="any" /></td>
                    <td><input type="number" class="form-control total_one_item" name="products[${rowIdx}][total_one_item]}" step="any" /></td>
                    <td><button type="button" class="btn btn-danger remove-row"><?php echo e(__('messages.Delete')); ?></button></td>
                </tr>
            `);
            rowIdx++;
            initializeProductSearch();
        });

        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
            updateSummary();
        });

        function initializeProductSearch() {
            $('.product-search').autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: '<?php echo e(route("products.search")); ?>',
                        dataType: 'json',
                        data: {
                            term: request.term
                        },
                        success: function(data) {
                            if (data.length === 0) {
                                response([{ label: 'Not Found', value: '' }]);
                            } else {
                                response($.map(data, function(item) {
                                    return {
                                        label: item.name,
                                        value: item.name,
                                        units: item.units,
                                        unit: item.unit,
                                        id: item.id,
                                        tax: item.tax,
                                        selling_price: item.selling_price
                                    };
                                }));
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching products:", error);
                        }
                    });
                },
                minLength: 2,
                select: function(event, ui) {
                    if (ui.item.value === '') {
                        event.preventDefault();
                    } else {
                        const selectedRow = $(this).closest('tr');
                        const unitDropdown = selectedRow.find('.product-unit');
                        unitDropdown.empty();

                        if (ui.item.unit) {
                            unitDropdown.append(`<option value="${ui.item.unit.id}" data-selling-price="${ui.item.unit.selling_price}">${ui.item.unit.name}</option>`);
                        }

                        if (ui.item.units) {
                            $.each(ui.item.units, function(index, unit) {
                                unitDropdown.append(`<option value="${unit.id}" data-selling-price="${unit.selling_price}">${unit.name}</option>`);
                            });
                        }

                        const sellingPriceWithTax = parseFloat(ui.item.selling_price);
                        const taxPercentage = parseFloat(ui.item.tax);
                        const sellingPriceWithoutTax = sellingPriceWithTax / (1 + taxPercentage / 100);

                        selectedRow.find('.selling_price_without_tax').val(sellingPriceWithoutTax.toFixed(2));
                        selectedRow.find('.tax').val(taxPercentage);
                        selectedRow.find('.selling_price_with_tax').val(sellingPriceWithTax.toFixed(2));

                        updateSummary();

                        unitDropdown.on('change', function() {
                            const selectedOption = $(this).find('option:selected');
                            const selectedSellingPrice = parseFloat(selectedOption.data('selling-price'));
                            const tax = parseFloat(selectedRow.find('.tax').val());

                            const newSellingPriceWithoutTax = selectedSellingPrice / (1 + tax / 100);
                            selectedRow.find('.selling_price_without_tax').val(newSellingPriceWithoutTax.toFixed(2));
                            selectedRow.find('.selling_price_with_tax').val(selectedSellingPrice.toFixed(2));

                            updateSummary();
                        });
                    }
                }
            });
        }

        $(document).on('change', '.quantity, .selling_price_without_tax, .tax, .line_discount_fixed, .line_discount_percentage, #coupon_discount_input', function() {
            updateSummary();
        });

        function updateSummary() {
            let totalBeforeTax = 0;
            let totalLineDiscount = 0;
            let totalCouponDiscount = 0;
            let totalTax = 0;
            let totalAmount = 0;
            let totalPriceAfterLineDiscounts = 0;

            const couponDiscountPercentage = parseFloat($('#coupon_discount_input').val()) || 0;
            const deliveryFee = parseFloat($('#delivery_fee').text()) || 0;

            $('#products_table tbody tr').each(function() {
                const quantity = parseFloat($(this).find('.quantity').val()) || 0;
                const sellingPriceWithoutTax = parseFloat($(this).find('.selling_price_without_tax').val()) || 0;
                const taxPercentage = parseFloat($(this).find('.tax').val()) || 0;
                const lineDiscountFixed = parseFloat($(this).find('.line_discount_fixed').val()) || 0;
                const lineDiscountPercentage = parseFloat($(this).find('.line_discount_percentage').val()) || 0;

                // Check if a discount value exists to avoid recalculating it
                let lineDiscountValue = parseFloat($(this).find('.line_discount_value').val()) || 0;
                
                if (lineDiscountValue === 0) {
                    // Total price before line discount
                    const totalPriceBeforeLineDiscount = sellingPriceWithoutTax * quantity;

                    // Apply line discounts only if the value hasn't been set yet
                    lineDiscountValue = (totalPriceBeforeLineDiscount * (lineDiscountPercentage / 100)) + lineDiscountFixed;
                    $(this).find('.line_discount_value').val(lineDiscountValue); // Save the calculated discount value
                }

                const totalPriceAfterLineDiscount = (sellingPriceWithoutTax * quantity) - lineDiscountValue;

                // Calculate the price after applying coupon discount
                const totalCouponDiscountValue = totalPriceAfterLineDiscount * (couponDiscountPercentage / 100);
                const totalPriceAfterAllDiscounts = totalPriceAfterLineDiscount - totalCouponDiscountValue;

                // Calculate tax based on the new total price after all discounts
                const totalRowTax = totalPriceAfterAllDiscounts * (taxPercentage / 100);

                // Calculate the total price for the item including tax and discounts
                const totalOneItem = totalPriceAfterAllDiscounts + totalRowTax;

                // Set the value for the total_one_item input field
                $(this).find('.total_one_item').val(totalOneItem.toFixed(2));

                // Accumulate values for summary calculations
                totalBeforeTax += sellingPriceWithoutTax * quantity;
                totalLineDiscount += lineDiscountValue;
                totalPriceAfterLineDiscounts += totalPriceAfterLineDiscount;
                totalTax += totalRowTax;
                totalAmount += totalOneItem;
            });

            // Calculate coupon discount on the total after line discounts
            totalCouponDiscount = totalPriceAfterLineDiscounts * (couponDiscountPercentage / 100);

            // Update the summary fields
            $('#total_selling_price').text(totalBeforeTax.toFixed(2));
            $('#total_discount').text(totalLineDiscount.toFixed(2));
            $('#coupon_discount').text(totalCouponDiscount.toFixed(2));
            $('#total_tax').text(totalTax.toFixed(2));
            $('#total_amount').text(totalAmount.toFixed(2));
        }


        initializeProductSearch();
    });
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u573862697/domains/vertex-jordan.online/public_html/resources/views/admin/orders/edit.blade.php ENDPATH**/ ?>