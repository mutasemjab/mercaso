@extends('layouts.admin')

@section('title')
{{ __('messages.products') }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-3 text-gray-800">{{ __('messages.products') }}</h1>
            <form method="GET" action="{{ route('product_report') }}">
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
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.products') }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <h3>
                            {{ __('messages.products') }}
                            <span class="badge badge-info">{{ $reportData['products']->count() }}</span>
                        </h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.ID') }}</th>
                                    <th>{{ __('messages.Name') }}</th>
                                    <th>{{ __('messages.Status') }}</th>
                                    <th>{{ __('messages.Category') }}</th>
                                    <th>{{ __('messages.UnitForNormalUser') }}</th>
                                    <th>{{ __('messages.PriceForNormalUser') }}</th>
                                    <th>{{ __('messages.UnitForWholeSale') }}</th>
                                    <th>{{ __('messages.PriceForWholeSale') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reportData['products'] as $index => $product)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $product->name_en }}</td>
                                    <td>{{ $product->status == 1 ? 'Active' : 'Not Active' }}</td>
                                    <td>{{ $product->category->name_en ?? null }}</td>
                                    <td>{{ $product->unit->name_en ?? null }}</td>
                                    <td>{{ $product->selling_price_for_user ?? null }}</td>
                                    <td>{{ $product->units->first()->name_en ?? null }}</td>
                                    <td>{{ $product->units->first()->pivot->selling_price ?? null }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

</div>
@endsection
