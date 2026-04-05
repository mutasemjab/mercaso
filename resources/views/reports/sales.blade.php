@extends('layouts.admin')

@section('title')
{{ __('messages.sales_report') }}
@endsection

@section('content')
<div class="container-fluid">

    {{-- ── Filters (hidden on print) ── --}}
    <div class="row mb-4 no-print">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar mr-2"></i>{{ __('messages.sales_report') }}</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('sales_report') }}" id="filterForm">
                        <div class="row">

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

                            <!-- Customer Type -->
                            <div class="col-md-2 mb-2">
                                <label class="font-weight-bold small">{{ __('messages.customer_type') }}</label>
                                <select name="user_type" class="form-control">
                                    <option value="">{{ __('messages.all') }}</option>
                                    <option value="1" @selected($userType == '1')>{{ __('messages.Retail') }}</option>
                                    <option value="2" @selected($userType == '2')>{{ __('messages.WholeSale') }}</option>
                                </select>
                            </div>

                            <!-- Category -->
                            <div class="col-md-3 mb-2">
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

                            <!-- Buttons -->
                            <div class="col-md-3 mb-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="fas fa-sync-alt mr-1"></i>{{ __('messages.Generate_Report') }}
                                </button>
                                <a href="{{ route('sales_report') }}" class="btn btn-outline-secondary mr-2">
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

    {{-- ── Summary Cards ── --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-left-primary shadow-sm h-100">
                <div class="card-body py-3">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{ __('messages.total_orders') }}</div>
                    <div class="h5 font-weight-bold text-gray-800">{{ number_format($summary['order_count']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-left-success shadow-sm h-100">
                <div class="card-body py-3">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{ __('messages.total_revenue') }}</div>
                    <div class="h5 font-weight-bold text-gray-800">${{ number_format($summary['total_revenue'], 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-left-info shadow-sm h-100">
                <div class="card-body py-3">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{ __('messages.total_tax') }}</div>
                    <div class="h5 font-weight-bold text-gray-800">${{ number_format($summary['total_tax'], 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-left-warning shadow-sm h-100">
                <div class="card-body py-3">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">{{ __('messages.total_discount') }}</div>
                    <div class="h5 font-weight-bold text-gray-800">${{ number_format($summary['total_discount'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Products Table ── --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center no-print">
                    <h6 class="m-0 font-weight-bold text-primary">
                        {{ __('messages.sold_products') }}
                        <span class="badge badge-info ml-2">{{ $products->count() }}</span>
                    </h6>
                </div>

                {{-- Print header --}}
                <div class="print-only text-center mb-3 pt-3">
                    <h4>{{ __('messages.sales_report') }}</h4>
                    <p class="mb-1">
                        {{ __('messages.From_Date') }}: <strong>{{ $fromDate }}</strong>
                        &nbsp;|&nbsp;
                        {{ __('messages.To_Date') }}: <strong>{{ $toDate }}</strong>
                        @if($userType)
                            &nbsp;|&nbsp; {{ __('messages.customer_type') }}: <strong>{{ $userType == 1 ? __('messages.Retail') : __('messages.WholeSale') }}</strong>
                        @endif
                        @if($categoryId)
                            &nbsp;|&nbsp; {{ __('messages.Category') }}: <strong>{{ $categories->firstWhere('id', $categoryId)?->{'name_'.app()->getLocale()} }}</strong>
                        @endif
                    </p>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('messages.product') }}</th>
                                    <th>{{ __('messages.Category') }}</th>
                                    <th class="text-center">{{ __('messages.orders_count') }}</th>
                                    <th class="text-center">{{ __('messages.total_quantity_sold') }}</th>
                                    <th class="text-right">{{ __('messages.total_discount') }}</th>
                                    <th class="text-right">{{ __('messages.total_tax') }}</th>
                                    <th class="text-right">{{ __('messages.total_revenue') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $i => $product)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        <strong>{{ $product->name_ar }}</strong>
                                        <br><small class="text-muted">{{ $product->name_en }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $catName = app()->getLocale() == 'ar' ? $product->cat_name_ar : $product->cat_name_en;
                                        @endphp
                                        <span class="badge badge-light border">{{ $catName ?? '-' }}</span>
                                    </td>
                                    <td class="text-center">{{ number_format($product->order_count) }}</td>
                                    <td class="text-center">
                                        <strong>{{ number_format($product->total_quantity) }}</strong>
                                    </td>
                                    <td class="text-right">${{ number_format($product->total_discount, 2) }}</td>
                                    <td class="text-right">${{ number_format($product->total_tax, 2) }}</td>
                                    <td class="text-right">
                                        <strong>${{ number_format($product->total_revenue, 2) }}</strong>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        {{ __('messages.No_data') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            @if($products->count() > 0)
                            <tfoot class="font-weight-bold bg-light">
                                <tr>
                                    <td colspan="4" class="text-right">{{ __('messages.Total') }}:</td>
                                    <td class="text-center">{{ number_format($summary['total_quantity']) }}</td>
                                    <td class="text-right">${{ number_format($summary['total_discount'], 2) }}</td>
                                    <td class="text-right">${{ number_format($summary['total_tax'], 2) }}</td>
                                    <td class="text-right">${{ number_format($summary['total_revenue'], 2) }}</td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    .bg-gradient-dark    { background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); }
    .border-left-primary { border-left: 4px solid #4e73df; }
    .border-left-success { border-left: 4px solid #1cc88a; }
    .border-left-info    { border-left: 4px solid #36b9cc; }
    .border-left-warning { border-left: 4px solid #f6c23e; }
    .print-only { display: none; }

    @media print {
        .no-print, .sidebar, nav, header,
        .main-header, .main-sidebar, .content-header { display: none !important; }

        .print-only { display: block !important; }

        .card { box-shadow: none !important; border: 1px solid #ccc !important; }
        body, .content-wrapper { margin: 0 !important; padding: 0 !important; background: #fff !important; }
        .container-fluid { padding: 4px !important; }
        .table th, .table td { padding: 4px 6px !important; font-size: 11px !important; }
        .border-left-primary, .border-left-success,
        .border-left-info, .border-left-warning { border-left-width: 3px !important; }
    }
</style>

<script>
document.getElementById('printBtn').addEventListener('click', function () {
    const form    = document.getElementById('filterForm');
    const params  = new URLSearchParams(new FormData(form));
    params.set('print', '1');
    window.location.href = '{{ route('sales_report') }}?' + params.toString();
});

@if($isPrint)
window.addEventListener('load', function () { window.print(); });
@endif
</script>

@endsection
