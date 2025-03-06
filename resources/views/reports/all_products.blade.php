@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-3 text-gray-800">{{ __('messages.product_move') }}</h1>
            <form method="GET" action="{{ route('product_move_all') }}">
                <div class="form-row align-items-end">
                    <div class="form-group col-md-3">
                        <label for="shop_id">{{ __('messages.shop') }}</label>
                        <select id="shop_id" name="shop_id" class="form-control" required>
                            <option value="">{{ __('messages.select_shop') }}</option>
                            @foreach($shops as $shop)
                                <option value="{{ $shop->id }}" {{ request('shop_id') == $shop->id ? 'selected' : '' }}>
                                    {{ $shop->name }}
                                </option>
                            @endforeach
                        </select>
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
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>{{ __('messages.product_name') }}</th>
                    <th>{{ __('messages.beginning_balance') }}</th>
                    <th>{{ __('messages.purchase_price') }}</th>
                    <th>{{ __('messages.in_quantity') }}</th>
                    <th>{{ __('messages.out_wholesale') }}</th>
                    <th>{{ __('messages.wholesale_unit_price') }}</th>
                    <th>{{ __('messages.wholesale_total') }}</th>
                    <th>{{ __('messages.out_normal') }}</th>
                    <th>{{ __('messages.normal_unit_price') }}</th>
                    <th>{{ __('messages.normal_total') }}</th>
                    <th>{{ __('messages.remaining') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reportData as $productData)
                    <tr>
                        <td>{{ $productData['product_name'] }}</td>
                        <td>{{ $productData['beginning_balance'] }}</td>
                        <td>{{ number_format($productData['purchase_price'], 2) }}</td>
                        <td>{{ $productData['in_quantity'] }}</td>
                        <td>{{ $productData['out_wholesale'] }}</td>
                        <td>{{ number_format($productData['wholesale_unit_price'], 2) }}</td>
                        <td>{{ number_format($productData['wholesale_total'], 2) }}</td>
                        <td>{{ $productData['out_normal'] }}</td>
                        <td>{{ number_format($productData['normal_unit_price'], 2) }}</td>
                        <td>{{ number_format($productData['normal_total'], 2) }}</td>
                        <td>{{ number_format($productData['remaining'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <p class="text-center">{{ __('messages.no_data_available') }}</p>
    @endif
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('#shop_id').select2({
            placeholder: 'Select Shop',
            allowClear: true
        });
    });
</script>
@endsection
