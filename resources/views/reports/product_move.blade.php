@extends('layouts.admin')

@section('title')
{{ __('messages.product_move') }}
@endsection


@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-3 text-gray-800">{{ __('messages.product_move') }}</h1>
            <form method="GET" action="{{ route('product_move') }}">
                <div class="form-row align-items-end">
                    <div class="form-group col-md-3">
                        <label for="shop_id">{{ __('messages.shop') }}</label>
                        <select id="shop_id" name="shop_id" class="form-control" required>
                            <option value="">{{ __('messages.select_shop') }}</option>
                            @foreach($shops as $shop)
                                <option value="{{ $shop->id }}" {{ request('shop_id') == $shop->id ? 'selected' : '' }}>{{ $shop->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="product_id">{{ __('messages.products') }}</label>
                        <select class="form-control" name="product_id" id="product_id">
                            <option value="">Select Product</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name_ar }}</option>
                            @endforeach
                        </select>
                        @error('product')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label for="from_date">{{ __('messages.from_date') }}</label>
                        <input type="date" id="from_date" name="from_date" class="form-control" value="{{ request('from_date', date('Y-m-01')) }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="to_date">{{ __('messages.to_date') }}</label>
                        <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date', date('Y-m-t')) }}">
                    </div>
                    <div class="form-group col-md-3">
                        <button type="submit" class="btn btn-primary">{{ __('messages.Show') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

   @if(!empty($reportData))
    <div class="table-responsive">
        {{-- Note Vouchers Section --}}
        @if(!empty($reportData['noteVouchers']))
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
                @foreach($reportData['noteVouchers'] as $voucher)
                    <tr>
                        <td>
                            <a href="{{ route('noteVouchers.edit', $voucher['voucher_id']) }}" class="btn btn-link" target="_blank">
                                {{ $voucher['voucher_id'] }}
                            </a>
                        </td>
                        <td>{{ $voucher['note'] }}</td>
                        <td>{{ $voucher['date_note_voucher'] }}</td>
                        <td>{{ $voucher['from_warehouse'] }}</td>
                        <td>
                            @if($voucher['note_voucher_type'] == 1 || $voucher['note_voucher_type'] == 3)
                                In
                            @elseif($voucher['note_voucher_type'] == 2)
                                Out
                            @endif
                        </td>
                        <td>{{ $voucher['unit_name'] ?? 'N/A' }}</td>
                        <td>{{ $voucher['quantity'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- Display totals for "in", "out", and net quantity --}}
        <h4>Total In: {{ $totalIn }}</h4>
        <h4>Total Out: {{ $totalOut }}</h4>
        <h4>Net Quantity: {{ $netQuantity }}</h4>
        @endif

        {{-- Orders Section --}}
        @if(!empty($reportData['orders']))
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
                @foreach($reportData['orders'] as $order)
                    <tr>
                        <td>
                            <a href="{{ route('orders.edit', $order['order_id']) }}" class="btn btn-link" target="_blank">
                                {{ $order['order_number'] }}
                            </a>
                        </td>
                        <td>{{ $order['user_name'] ?? 'N/A' }}</td>
                        <td>{{ $order['date'] }}</td>
                        <td>{{ $order['total_prices'] }}</td>
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
                                    @foreach($order['order_products'] as $orderProduct)
                                        <tr>
                                            <td>{{ $orderProduct['product_name'] }}</td>
                                            <td>{{ $orderProduct['quantity'] }}</td>
                                            <td>{{ $orderProduct['unit_price'] }}</td>
                                            <td>{{ $orderProduct['total_price_after_tax'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4"><hr></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
@endif

</div>
@endsection


@section('script')
<script>
    $(document).ready(function() {
        $('#product_id').select2({
            placeholder: 'Select Product',
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('products.search') }}',
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
@endsection

