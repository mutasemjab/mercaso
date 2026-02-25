@extends('layouts.admin')

@section('title')
{{ __('messages.products') }}
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-3 text-gray-800">{{ __('messages.product_reports') }}</h1>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-filter mr-2"></i>{{ __('messages.report_filter') }}</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('product_report') }}" class="form-inline justify-content-center flex-wrap">
                        <div class="form-group {{ app()->getLocale() == 'ar' ? 'ml-3' : 'mr-3' }} mb-2">
                            <label for="search" class="{{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }} font-weight-bold">
                                {{ __('messages.search') }}:
                            </label>
                            <input type="text"
                                   name="search"
                                   id="search"
                                   class="form-control"
                                   placeholder="Search by name, number, or barcode..."
                                   value="{{ request('search') }}">
                        </div>

                        <div class="form-group {{ app()->getLocale() == 'ar' ? 'ml-3' : 'mr-3' }} mb-2">
                            <label for="from_date" class="{{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }} font-weight-bold">
                                {{ __('messages.From_Date') }}:
                            </label>
                            <input type="date"
                                   name="from_date"
                                   id="from_date"
                                   class="form-control"
                                   value="{{ \Carbon\Carbon::parse($fromDate)->format('Y-m-d') }}"
                                   required>
                        </div>

                        <div class="form-group {{ app()->getLocale() == 'ar' ? 'ml-3' : 'mr-3' }} mb-2">
                            <label for="to_date" class="{{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }} font-weight-bold">
                                {{ __('messages.To_Date') }}:
                            </label>
                            <input type="date"
                                   name="to_date"
                                   id="to_date"
                                   class="form-control"
                                   value="{{ \Carbon\Carbon::parse($toDate)->format('Y-m-d') }}"
                                   required>
                        </div>

                        <div class="form-group {{ app()->getLocale() == 'ar' ? 'ml-3' : 'mr-3' }} mb-2">
                            <label for="brand_id" class="{{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }} font-weight-bold">
                                {{ __('messages.Brand') }}:
                            </label>
                            <select name="brand_id" id="brand_id" class="form-control">
                                <option value="">{{ __('messages.all') }}</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" @selected($brandId == $brand->id)>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group {{ app()->getLocale() == 'ar' ? 'ml-3' : 'mr-3' }} mb-2">
                            <label for="category_id" class="{{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }} font-weight-bold">
                                {{ __('messages.Category') }}:
                            </label>
                            <select name="category_id" id="category_id" class="form-control">
                                <option value="">{{ __('messages.all') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected($categoryId == $category->id)>
                                        {{ app()->getLocale() == 'ar' ? $category->name_ar : $category->name_en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group {{ app()->getLocale() == 'ar' ? 'ml-3' : 'mr-3' }} mb-2">
                            <label for="tax_id" class="{{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }} font-weight-bold">
                                {{ __('messages.Tax') }}:
                            </label>
                            <select name="tax_id" id="tax_id" class="form-control">
                                <option value="">{{ __('messages.all') }}</option>
                                @foreach($taxes as $tax)
                                    <option value="{{ $tax->id }}" @selected($taxId == $tax->id)>
                                        {{ $tax->name }} ({{ $tax->value }}%)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg {{ app()->getLocale() == 'ar' ? 'ml-2' : 'ml-2' }} mb-2">
                            <i class="fas fa-sync-alt mr-2"></i>{{ __('messages.Generate_Report') }}
                        </button>

                        <a href="{{ route('product_report') }}" class="btn btn-outline-secondary btn-lg {{ app()->getLocale() == 'ar' ? 'ml-2' : 'ml-2' }} mb-2">
                            <i class="fas fa-redo mr-2"></i>{{ __('messages.Reset') }}
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($reportData))
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.products') }}</h6>
                    <button onclick="window.print()" class="btn btn-success btn-sm no-print">
                        <i class="fas fa-print {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                        {{ __('messages.Print') }}
                    </button>
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
                                    <td>${{ number_format($product->selling_price_for_user ?? 0, 2) }}</td>
                                    <td>{{ $product->units->first()->name_en ?? null }}</td>
                                    <td>${{ number_format($product->units->first()->pivot->selling_price ?? 0, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $reportData['products']->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

</div>

<!-- Styles -->
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    /* RTL Support */
    @media (prefers-direction: rtl) {
        .mr-2 { margin-left: 0.5rem !important; margin-right: 0 !important; }
        .ml-2 { margin-right: 0.5rem !important; margin-left: 0 !important; }
        .mr-3 { margin-left: 0.75rem !important; margin-right: 0 !important; }
        .ml-3 { margin-right: 0.75rem !important; margin-left: 0 !important; }
    }

    /* Print Styles */
    @media print {
        /* Hide everything except the table */
        .form-inline, .no-print, .pagination, .card-header, h3, .badge {
            display: none !important;
        }

        .card {
            box-shadow: none !important;
            border: none !important;
        }

        .card-body {
            padding: 5px !important;
        }

        body {
            margin: 0;
            padding: 5px;
        }

        .container-fluid {
            padding: 0 !important;
        }

        .table {
            margin-bottom: 0 !important;
        }

        /* Make table font smaller for better fitting */
        .table th,
        .table td {
            padding: 4px !important;
            font-size: 12px !important;
        }
    }
</style>

@endsection
