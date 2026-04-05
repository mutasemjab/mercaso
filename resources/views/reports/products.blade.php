@extends('layouts.admin')

@section('title')
{{ __('messages.products') }}
@endsection

@section('content')
<div class="container-fluid">

    <!-- Filter Section (hidden on print) -->
    <div class="row mb-4 no-print">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-filter mr-2"></i>{{ __('messages.report_filter') }}</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('product_report') }}" id="filterForm">
                        <div class="row">
                            <!-- Search -->
                            <div class="col-md-3 mb-2">
                                <label class="font-weight-bold small">{{ __('messages.search') }}</label>
                                <input type="text" name="search" class="form-control"
                                       placeholder="{{ __('messages.search') }}..."
                                       value="{{ $search }}">
                            </div>

                            <!-- From Date -->
                            <div class="col-md-2 mb-2">
                                <label class="font-weight-bold small">{{ __('messages.From_Date') }}</label>
                                <input type="date" name="from_date" class="form-control"
                                       value="{{ \Carbon\Carbon::parse($fromDate)->format('Y-m-d') }}" required>
                            </div>

                            <!-- To Date -->
                            <div class="col-md-2 mb-2">
                                <label class="font-weight-bold small">{{ __('messages.To_Date') }}</label>
                                <input type="date" name="to_date" class="form-control"
                                       value="{{ \Carbon\Carbon::parse($toDate)->format('Y-m-d') }}" required>
                            </div>

                            <!-- Category -->
                            <div class="col-md-2 mb-2">
                                <label class="font-weight-bold small">{{ __('messages.Category') }}</label>
                                <select name="category_id" class="form-control">
                                    <option value="">{{ __('messages.all') }}</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" @selected($categoryId == $cat->id)>
                                            {{ app()->getLocale() == 'ar' ? $cat->name_ar : $cat->name_en }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Status -->
                            <div class="col-md-1 mb-2">
                                <label class="font-weight-bold small">{{ __('messages.Status') }}</label>
                                <select name="status" class="form-control">
                                    <option value="">{{ __('messages.all') }}</option>
                                    <option value="1" @selected($status == '1')>{{ __('messages.active') }}</option>
                                    <option value="2" @selected($status == '2')>{{ __('messages.Not_Active') }}</option>
                                </select>
                            </div>

                            <!-- In Stock -->
                            <div class="col-md-2 mb-2">
                                <label class="font-weight-bold small">{{ __('messages.Stock') }}</label>
                                <select name="in_stock" class="form-control">
                                    <option value="">{{ __('messages.all') }}</option>
                                    <option value="1" @selected($inStock == '1')>{{ __('messages.In_Stock') }}</option>
                                    <option value="2" @selected($inStock == '2')>{{ __('messages.Out_Of_Stock') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <!-- Brand -->
                            <div class="col-md-3 mb-2">
                                <label class="font-weight-bold small">{{ __('messages.Brand') }}</label>
                                <select name="brand_id" class="form-control">
                                    <option value="">{{ __('messages.all') }}</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" @selected($brandId == $brand->id)>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tax -->
                            <div class="col-md-3 mb-2">
                                <label class="font-weight-bold small">{{ __('messages.Tax') }}</label>
                                <select name="tax_id" class="form-control">
                                    <option value="">{{ __('messages.all') }}</option>
                                    @foreach($taxes as $tax)
                                        <option value="{{ $tax->id }}" @selected($taxId == $tax->id)>
                                            {{ $tax->name }} ({{ $tax->value }}%)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-2 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="fas fa-sync-alt mr-1"></i>{{ __('messages.Generate_Report') }}
                                </button>
                                <a href="{{ route('product_report') }}" class="btn btn-outline-secondary mr-2">
                                    <i class="fas fa-redo mr-1"></i>{{ __('messages.Reset') }}
                                </a>
                                <button type="button" class="btn btn-success" id="printBtn">
                                    <i class="fas fa-print mr-1"></i>{{ __('messages.Print') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($reportData))
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center no-print">
                    <h6 class="m-0 font-weight-bold text-primary">
                        {{ __('messages.products') }}
                        <span class="badge badge-info ml-2">
                            {{ $isPrint ? $products->count() : $products->total() }}
                        </span>
                    </h6>
                </div>

                <!-- Print header (only visible when printing) -->
                <div class="print-only text-center mb-3">
                    <h4>{{ __('messages.product_reports') }}</h4>
                    <small>{{ __('messages.From_Date') }}: {{ $fromDate }} &nbsp;|&nbsp; {{ __('messages.To_Date') }}: {{ $toDate }}</small>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('messages.Name') }}</th>
                                    <th>{{ __('messages.Status') }}</th>
                                    <th>{{ __('messages.Stock') }}</th>
                                    <th>{{ __('messages.Category') }}</th>
                                    <th>{{ __('messages.UnitForNormalUser') }}</th>
                                    <th>{{ __('messages.PriceForNormalUser') }}</th>
                                    <th>{{ __('messages.UnitForWholeSale') }}</th>
                                    <th>{{ __('messages.PriceForWholeSale') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $index => $product)
                                <tr>
                                    <td>{{ $isPrint ? $index + 1 : $products->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $product->name_ar }}</strong>
                                        <br><small class="text-muted">{{ $product->name_en }}</small>
                                    </td>
                                    <td>
                                        @if($product->status == 1)
                                            <span class="badge badge-success">{{ __('messages.active') }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ __('messages.Not_Active') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->in_stock == 1)
                                            <span class="badge badge-success">{{ __('messages.In_Stock') }}</span>
                                        @else
                                            <span class="badge badge-warning">{{ __('messages.Out_Of_Stock') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ app()->getLocale() == 'ar' ? ($product->category->name_ar ?? '-') : ($product->category->name_en ?? '-') }}</td>
                                    <td>{{ $product->unit->name_en ?? '-' }}</td>
                                    <td>${{ number_format($product->selling_price_for_user ?? 0, 2) }}</td>
                                    <td>{{ $product->units->first()->name_en ?? '-' }}</td>
                                    <td>${{ number_format($product->units->first()->pivot->selling_price ?? 0, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-3">{{ __('messages.No_data') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination (hidden in print mode) -->
                    @if(!$isPrint && $products->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4 no-print">
                        <div class="text-muted small">
                            Showing {{ $products->firstItem() }} – {{ $products->lastItem() }} of {{ $products->total() }}
                        </div>
                        {{ $products->appends(request()->except('print'))->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

<style>
    .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .print-only { display: none; }

    @media print {
        .no-print, .sidebar, nav, header, .main-header, .main-sidebar,
        .content-header, .breadcrumb { display: none !important; }

        .print-only { display: block !important; }

        .card { box-shadow: none !important; border: 1px solid #dee2e6 !important; }
        .card-body { padding: 4px !important; }
        body, .content-wrapper { margin: 0 !important; padding: 0 !important; background: #fff !important; }
        .container-fluid { padding: 4px !important; }

        .table th, .table td { padding: 4px 6px !important; font-size: 11px !important; }
        .badge { border: 1px solid #999; padding: 2px 5px; font-size: 10px; }
    }
</style>

<script>
document.getElementById('printBtn').addEventListener('click', function () {
    // Build current filter params and append print=1, then navigate
    const form = document.getElementById('filterForm');
    const params = new URLSearchParams(new FormData(form));
    params.set('print', '1');
    window.location.href = '{{ route('product_report') }}?' + params.toString();
});

// Auto-print when in print mode
@if($isPrint)
window.addEventListener('load', function () {
    window.print();
});
@endif
</script>

@endsection
