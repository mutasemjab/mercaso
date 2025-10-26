@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Edit Order</h2>
    <form action="{{ route('orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="redirect_to" id="redirect_to" value="index">

        @if($order->order_status == 6 || $order->order_status == 4)
       @else
        <h6 style="color: red"> Just Click One Click Only </h6>
        <button type="submit" class="btn btn-primary" onclick="setRedirect('index')">{{ __('messages.Submit') }}</button>
        <button type="submit" class="btn btn-primary" onclick="setRedirect('show')">{{ __('messages.Save_Print') }}</button>
        @endif


        @if($order->order_status == 6)
        <h3 style="color:red;">{{ __('messages.Refund') }}</h3>
        @else
        <div class="col-md-6">
            <div class="form-group mt-3">
                <label for="order_status">{{ __('messages.order_status') }}</label>
                <select name="order_status" class="form-control" required>
                    <option value="1" {{ $order->order_status == 1 ? 'selected' : '' }}>{{ __('messages.Pending') }}</option>
                    <option value="2" {{ $order->order_status == 2 ? 'selected' : '' }}>{{ __('messages.Accepted') }}</option>
                    <option value="3" {{ $order->order_status == 3 ? 'selected' : '' }}>{{ __('messages.OnTheWay') }}</option>
                    <option value="4" {{ $order->order_status == 4 ? 'selected' : '' }}>{{ __('messages.Delivered') }}</option>
                    <option value="5" {{ $order->order_status == 5 ? 'selected' : '' }}>{{ __('messages.Canceled') }}</option>
                </select>
            </div>
        </div>
        @endif
        <div class="col-md-6">
            <div class="form-group">
                <label for="date">{{ __('messages.Date') }}</label>
                <input type="date" name="date" class="form-control" value="{{ \Carbon\Carbon::parse($order->date)->format('Y-m-d') }}" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group mt-3">
                <label for="payment_type">{{ __('messages.payment_type') }}</label>
                <select name="payment_type" class="form-control" required>
                    <option value="1" {{ $order->payment_type == 1 ? 'selected' : '' }}>{{ __('messages.Cash') }}</option>
                    <option value="2" {{ $order->payment_type == 2 ? 'selected' : '' }}>{{ __('messages.Receivables') }}</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mt-3">
                <label for="userSearch">{{ __('messages.User') }}</label>
                <input type="text" id="userSearch" class="form-control" name="user" value="{{ $order->user->name }}" required />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mt-3">
                <label for="address">{{ __('messages.Address') }}</label>
                <select id="addressSelect" class="form-control" name="address" required>
                    <option value="">{{ __('messages.select_address') }}</option>
                    @foreach ($order->user->addresses as $address)
                    <option value="{{ $address->id }}" {{ $order->address_id == $address->id ? 'selected' : '' }}>{{ $address->address }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group mt-3">
                <label for="coupon_discount">{{ __('messages.Coupon Discount (%)') }}</label>
                <input type="number" name="coupon_discount" class="form-control" step="0.01" value="{{ $order->coupon_discount }}" id="coupon_discount_input" required>
            </div>
        </div>

        <br>
        <table class="table table-bordered" id="products_table">
            <thead>
                <tr>
                    <th>{{ __('messages.product') }}</th>
                    <th>{{ __('messages.unit') }}</th>
                    <th>{{ __('messages.quantity') }}</th>
                    <th>{{ __('messages.selling_price_without_tax') }}</th>
                    <th>{{ __('messages.selling_price_with_tax') }}</th>
                    <th>{{ __('messages.tax') }}</th>
                    <th>{{ __('messages.line_discount_percentage') }}</th>

                    <th>{{ __('messages.line_discount_fixed') }}</th>
                    <th>{{ __('messages.total_one_item') }}</th>
                    <th>{{ __('messages.Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->products as $index => $orderProduct)
                <tr>
                    <td><input type="text" class="form-control product-search" name="products[{{ $index }}][name]" value="{{ $orderProduct->name_ar }}" /></td>
                    <td>
                        <select class="form-control product-unit" name="products[{{ $index }}][unit]">
                            <option value="">Select Unit</option>
                            @foreach ($orderProduct->units as $unit)
                                <option value="{{ $unit->id }}" {{ $orderProduct->pivot->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name_ar }}</option>
                            @endforeach
                            @if ($orderProduct->unit)
                                <option value="{{ $orderProduct->unit->id }}" {{ $orderProduct->pivot->unit_id == $orderProduct->unit->id ? 'selected' : '' }}>{{ $orderProduct->unit->name_ar }}</option>
                            @endif
                        </select>
                    </td>

                    @php
                    $sellingPriceWithoutTax = round($orderProduct->pivot->unit_price / (1 + ($orderProduct->pivot->tax_percentage / 100)), 3);
                    $sellingPriceWithTax = round($orderProduct->pivot->unit_price, 3);
                    $TaxPercentage = round($orderProduct->pivot->tax_percentage, 3);
                    @endphp

                    <td><input type="number" class="form-control quantity" name="products[{{ $index }}][quantity]" value="{{ $orderProduct->pivot->quantity }}" /></td>
                    <td><input type="number" class="form-control selling_price_without_tax" name="products[{{ $index }}][selling_price_without_tax]" step="any" value="{{ $sellingPriceWithoutTax }}" /></td>
                    <td><input type="number" class="form-control selling_price_with_tax" name="products[{{ $index }}][selling_price_with_tax]" step="any" value="{{ $sellingPriceWithTax }}" /></td>
                    <td><input type="number" class="form-control tax" name="products[{{ $index }}][tax]" step="any" value="{{ $TaxPercentage }}" /></td>

                    <td>
                        <!-- Input for line_discount_percentage (editable by user) -->
                        <input type="number" class="form-control line_discount_percentage" name="products[{{ $index }}][line_discount_percentage]" step="any" value="{{ $orderProduct->pivot->line_discount_percentage }}" />
                        <!-- Store original percentage discount for backend comparison -->
                        <input type="hidden" name="products[{{ $index }}][original_line_discount_percentage]" value="{{ $orderProduct->pivot->line_discount_percentage }}" />
                    </td>
                    <td>
                        <!-- Input for line_discount_value (editable by user) -->
                        <input type="number" class="form-control line_discount_value" name="products[{{ $index }}][line_discount_value]" step="any" value="{{ $orderProduct->pivot->line_discount_value }}" />
                        <!-- Store original line discount value for backend comparison -->
                        <input type="hidden" name="products[{{ $index }}][original_line_discount_value]" value="{{ $orderProduct->pivot->line_discount_value }}" />
                    </td>



                    <td><input type="number" class="form-control total_one_item" name="products[{{ $index }}][total_one_item]" step="any" value="{{ $orderProduct->pivot->total_one_item }}" /></td>
                    <td><button type="button" class="btn btn-danger remove-row">{{ __('messages.Delete') }}</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button type="button" class="btn btn-primary" id="add_row">{{ __('messages.Add_Row') }}</button>

        <!-- Summary Table -->
        <div class="mt-5">
            <h4>Summary</h4>
         <table class="table table-bordered" id="summary_table">
            <tbody>
                <tr>
                    <td>{{ __('messages.total_selling_price') }}</td>
                    <td><span id="total_selling_price">$ 
                        @php
                            $totalSellingPrice = 0;
                            foreach ($order->products as $product) {
                                $totalSellingPrice += ($product->pivot->quantity * ($product->pivot->unit_price / (1 + ($product->pivot->tax_percentage / 100))));
                            }
                        @endphp
                        {{ round($totalSellingPrice, 2) }}
                    </span></td>
                </tr>
                <tr>
                    <td>{{ __('messages.total_discount') }}</td>
                    <td><span id="total_discount">$ 
                        @php
                            $totalLineDiscounts = 0;
                            foreach ($order->products as $product) {
                                $totalLineDiscounts += $product->pivot->line_discount_value;
                            }
                        @endphp
                        {{ round($totalLineDiscounts, 2) }}
                    </span></td>
                </tr>
                <tr>
                    <td>{{ __('messages.coupon_discount') }}</td>
                    <td><span id="coupon_discount">$ 
                        @php
                            // Calculate coupon discount properly
                            $totalAfterLineDiscounts = $totalSellingPrice - $totalLineDiscounts;
                            $couponDiscountAmount = ($totalAfterLineDiscounts * $order->coupon_discount) / 100;
                        @endphp
                        {{ round($couponDiscountAmount, 2) }}
                    </span></td>
                </tr>

                @php
                $taxGroups = $order->products->groupBy('pivot.tax_percentage')->map(function ($group) {
                    return $group->sum('pivot.tax_value');
                });
                @endphp

                @foreach ($taxGroups as $taxPercentage => $taxValue)
                <tr>
                    <td>{{ __('messages.total_tax') }} ({{ $taxPercentage }}%)</td>
                    <td><span id="total_tax_{{ $taxPercentage }}"> $ {{ round($taxValue, 3) }} </span></td>
                </tr>
                @endforeach

                <tr>
                    <td>{{ __('messages.delivery_fee') }}</td>
                    <td><span id="delivery_fee">$ {{ $order->delivery_fee }} </span></td>
                </tr>
                <tr>
                    <td>{{ __('messages.total_amount') }}</td>
                    <td><span id="total_amount">$ {{ $order->total_prices }} </span></td>
                </tr>
            </tbody>
        </table>
        </div>
    </form>
</div>
@endsection

@section('js')
<script type="text/javascript">
    function setRedirect(value) {
        document.getElementById('redirect_to').value = value;
    }

    $(document).ready(function() {
        // Initialize user search autocomplete
        $('#userSearch').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: '{{ route("search.users") }}',
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
                document.getElementById('addressSelect').innerHTML = '<option value="">{{ __('messages.select_address') }}</option>';
                return;
            }

            fetch(`{{ url('user') }}/${userId}/addresses`)
                .then(response => response.json())
                .then(data => {
                    const addressSelect = document.getElementById('addressSelect');
                    addressSelect.innerHTML = '<option value="">{{ __('messages.select_address') }}</option>';

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

        let rowIdx = {{ $order->products->count() }};

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
                    <td><button type="button" class="btn btn-danger remove-row">{{ __('messages.Delete') }}</button></td>
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
                        url: '{{ route("products.search") }}',
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
        let totalSellingPrice = 0;  // Total before any discounts or taxes
        let totalLineDiscount = 0;  // Total line discounts
        let totalTax = 0;          // Total tax amount
        let totalAmount = 0;       // Final total amount
        let totalAfterLineDiscounts = 0; // Total after line discounts (before coupon)

        const couponDiscountPercentage = parseFloat($('#coupon_discount_input').val()) || 0;
        const deliveryFee = parseFloat($('#delivery_fee').text().replace('$', '').trim()) || 0;

        // First pass: Calculate totals and line discounts
        $('#products_table tbody tr').each(function() {
            const quantity = parseFloat($(this).find('.quantity').val()) || 0;
            const sellingPriceWithoutTax = parseFloat($(this).find('.selling_price_without_tax').val()) || 0;
            const lineDiscountFixed = parseFloat($(this).find('.line_discount_value').val()) || 0;
            const lineDiscountPercentage = parseFloat($(this).find('.line_discount_percentage').val()) || 0;

            // Total selling price before any discounts (this is what should show in total_selling_price)
            const itemTotalBeforeDiscounts = sellingPriceWithoutTax * quantity;
            totalSellingPrice += itemTotalBeforeDiscounts;

            // Calculate line discount value
            let lineDiscountValue = lineDiscountFixed;
            if (lineDiscountValue === 0 && lineDiscountPercentage > 0) {
                lineDiscountValue = itemTotalBeforeDiscounts * (lineDiscountPercentage / 100);
                $(this).find('.line_discount_value').val(lineDiscountValue.toFixed(2));
            }

            totalLineDiscount += lineDiscountValue;
            totalAfterLineDiscounts += (itemTotalBeforeDiscounts - lineDiscountValue);
        });

        // Calculate total coupon discount
        const totalCouponDiscount = totalAfterLineDiscounts * (couponDiscountPercentage / 100);

        // Second pass: Calculate individual item totals with proportional coupon discount and taxes
        $('#products_table tbody tr').each(function() {
            const quantity = parseFloat($(this).find('.quantity').val()) || 0;
            const sellingPriceWithoutTax = parseFloat($(this).find('.selling_price_without_tax').val()) || 0;
            const taxPercentage = parseFloat($(this).find('.tax').val()) || 0;
            const lineDiscountValue = parseFloat($(this).find('.line_discount_value').val()) || 0;

            // Item total before discounts
            const itemTotalBeforeDiscounts = sellingPriceWithoutTax * quantity;
            
            // Item total after line discount
            const itemTotalAfterLineDiscount = itemTotalBeforeDiscounts - lineDiscountValue;

            // Apply proportional coupon discount to this item
            let itemCouponDiscount = 0;
            if (totalAfterLineDiscounts > 0) {
                const itemProportion = itemTotalAfterLineDiscount / totalAfterLineDiscounts;
                itemCouponDiscount = totalCouponDiscount * itemProportion;
            }

            // Item total after all discounts
            const itemTotalAfterAllDiscounts = itemTotalAfterLineDiscount - itemCouponDiscount;

            // Calculate tax on the final discounted amount
            const itemTax = itemTotalAfterAllDiscounts * (taxPercentage / 100);

            // Final total for this item (after discounts + tax)
            const totalOneItem = itemTotalAfterAllDiscounts + itemTax;

            // Update the total_one_item field
            $(this).find('.total_one_item').val(totalOneItem.toFixed(2));

            // Add to totals
            totalTax += itemTax;
            totalAmount += totalOneItem;
        });

        // Update the summary display
        $('#total_selling_price').text('$ ' + totalSellingPrice.toFixed(2));
        $('#total_discount').text('$ ' + totalLineDiscount.toFixed(2));
        $('#coupon_discount').text('$ ' + totalCouponDiscount.toFixed(2));
        $('#total_tax').text('$ ' + totalTax.toFixed(2));
        $('#total_amount').text('$ ' + (totalAmount + deliveryFee).toFixed(2));
    }

        initializeProductSearch();
    });
</script>
@endsection

