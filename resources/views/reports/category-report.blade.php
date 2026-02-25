@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4 no-print">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-4">
                    <h1 class="display-4 font-weight-bold text-primary mb-2">
                        <i class="fas fa-list mr-2"></i>{{ __('messages.category_report') }}
                    </h1>
                    <p class="lead text-muted mb-1">{{ __('messages.sales_and_inventory_overview') }}</p>
                    <p class="text-muted">
                        <i class="far fa-calendar-alt mr-2"></i>
                        {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4 no-print">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-filter mr-2"></i>{{ __('messages.report_filter') }}</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('category-report.index') }}" class="form-inline justify-content-center flex-wrap">
                        <div class="form-group {{ app()->getLocale() == 'ar' ? 'ml-3' : 'mr-3' }} mb-2">
                            <label for="start_date" class="{{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }} font-weight-bold">
                                {{ __('messages.From_Date') }}:
                            </label>
                            <input type="date"
                                   name="start_date"
                                   id="start_date"
                                   class="form-control"
                                   value="{{ \Carbon\Carbon::parse($startDate)->format('Y-m-d') }}"
                                   required>
                        </div>

                        <div class="form-group {{ app()->getLocale() == 'ar' ? 'ml-3' : 'mr-3' }} mb-2">
                            <label for="end_date" class="{{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }} font-weight-bold">
                                {{ __('messages.To_Date') }}:
                            </label>
                            <input type="date"
                                   name="end_date"
                                   id="end_date"
                                   class="form-control"
                                   value="{{ \Carbon\Carbon::parse($endDate)->format('Y-m-d') }}"
                                   required>
                        </div>

                        <div class="form-group {{ app()->getLocale() == 'ar' ? 'ml-3' : 'mr-3' }} mb-2">
                            <label for="parent_category_id" class="{{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }} font-weight-bold">
                                {{ __('messages.parent_category') }}:
                            </label>
                            <select name="parent_category_id" id="parent_category_id" class="form-control">
                                <option value="">{{ __('messages.all') }}</option>
                                @foreach($parentCategories as $category)
                                    <option value="{{ $category->id }}" @selected($parentCategoryId == $category->id)>
                                        {{ app()->getLocale() == 'ar' ? $category->name_ar : $category->name_en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg {{ app()->getLocale() == 'ar' ? 'ml-2' : 'ml-2' }} mb-2">
                            <i class="fas fa-sync-alt mr-2"></i>{{ __('messages.Generate_Report') }}
                        </button>

                        <a href="{{ route('category-report.index') }}" class="btn btn-outline-secondary btn-lg {{ app()->getLocale() == 'ar' ? 'ml-2' : 'ml-2' }} mb-2">
                            <i class="fas fa-redo mr-2"></i>{{ __('messages.Reset') }}
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4 no-print">
        <div class="col-md-3 mb-3">
            <div class="card border-left-primary shadow-sm h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                {{ __('messages.total_sales_after_tax') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                ${{ number_format($totals['total_sales_after_tax'], 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-left-success shadow-sm h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                {{ __('messages.total_quantity_sold') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($totals['total_quantity_sold']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-left-info shadow-sm h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                {{ __('messages.total_products') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($totals['total_products']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-layer-group fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-left-warning shadow-sm h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                {{ __('messages.total_orders') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($totals['total_orders']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cart-plus fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-dark text-white no-print">
                    <h5 class="mb-0"><i class="fas fa-table mr-2"></i>{{ __('messages.categories') }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0 table-hover report-table" id="reportTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>{{ __('messages.category_name') }}</th>
                                    <th class="text-right">{{ __('messages.total_quantity_sold') }}</th>
                                    <th class="text-right">{{ __('messages.total_sales_before_tax') }}</th>
                                    <th class="text-right">{{ __('messages.total_discount') }}</th>
                                    <th class="text-right">{{ __('messages.total_tax') }}</th>
                                    <th class="text-right">{{ __('messages.total_sales_after_tax') }}</th>
                                    <th class="text-center">{{ __('messages.total_orders') }}</th>
                                    <th class="text-center">{{ __('messages.total_products') }}</th>
                                    <th class="text-center">{{ __('messages.in_stock_count') }}</th>
                                    <th class="text-center">{{ __('messages.out_of_stock_count') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categoryReport as $index => $category)
                                    @include('reports.partials.category-row', ['category' => $category, 'index' => $index + 1])
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                        <p class="text-muted">{{ __('messages.No_data') }}</p>
                                    </td>
                                </tr>
                                @endforelse

                                @if(!empty($categoryReport))
                                <tr class="total-row">
                                    <td colspan="2" class="text-center font-weight-bold">{{ __('messages.Total') }}</td>
                                    <td class="text-right font-weight-bold">{{ number_format($totals['total_quantity_sold']) }}</td>
                                    <td class="text-right font-weight-bold">${{ number_format($totals['total_sales_before_tax'], 2) }}</td>
                                    <td class="text-right font-weight-bold">${{ number_format($totals['total_discount'], 2) }}</td>
                                    <td class="text-right font-weight-bold">${{ number_format($totals['total_tax'], 2) }}</td>
                                    <td class="text-right font-weight-bold">${{ number_format($totals['total_sales_after_tax'], 2) }}</td>
                                    <td class="text-center font-weight-bold no-print">{{ number_format($totals['total_orders']) }}</td>
                                    <td class="text-center font-weight-bold no-print">{{ number_format($totals['total_products']) }}</td>
                                    <td class="text-center font-weight-bold no-print">{{ number_format($totals['total_in_stock']) }}</td>
                                    <td class="text-center font-weight-bold no-print">{{ number_format($totals['total_out_of_stock']) }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-muted text-center no-print">
                    <small>
                        <i class="far fa-clock mr-1"></i>
                        {{ __('messages.report_generated_on') }} {{ now()->format('M d, Y H:i A') }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mt-4 no-print">
        <div class="col-12 text-center">
            <form method="GET" action="{{ route('category-report.export') }}" style="display: inline;">
                <input type="hidden" name="start_date" value="{{ \Carbon\Carbon::parse($startDate)->format('Y-m-d') }}">
                <input type="hidden" name="end_date" value="{{ \Carbon\Carbon::parse($endDate)->format('Y-m-d') }}">
                <input type="hidden" name="parent_category_id" value="{{ $parentCategoryId ?? '' }}">
                <button type="submit" class="btn btn-success btn-lg {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}">
                    <i class="fas fa-file-excel mr-2"></i>{{ __('messages.export_report') }}
                </button>
            </form>

            <button class="btn btn-primary btn-lg" onclick="window.print()">
                <i class="fas fa-print mr-2"></i>{{ __('messages.Print') }}
            </button>
        </div>
    </div>
</div>

<!-- Styles -->
<style>
    .border-left-primary {
        border-left: 4px solid #4e73df;
    }
    .border-left-success {
        border-left: 4px solid #1cc88a;
    }
    .border-left-info {
        border-left: 4px solid #36b9cc;
    }
    .border-left-warning {
        border-left: 4px solid #f6c23e;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .bg-gradient-dark {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    }

    /* Report Table Styles */
    .report-table {
        font-size: 0.95rem;
    }

    .report-table thead th {
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .report-table tbody td {
        vertical-align: middle;
        padding: 0.6rem 0.5rem;
    }

    .report-table tbody tr {
        transition: background-color 0.2s ease;
    }

    .report-table tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.08);
    }

    .total-row {
        background-color: #2c3e50;
        color: white;
        font-weight: 600;
        border-top: 3px solid #2c3e50;
    }

    .total-row td {
        color: white;
    }

    /* RTL Support */
    @media (prefers-direction: rtl) {
        .border-left-primary,
        .border-left-success,
        .border-left-info,
        .border-left-warning {
            border-left: none;
            border-right: 4px solid;
        }
    }

    /* Print Styles */
    @media print {
        .no-print {
            display: none !important;
        }

        * {
            margin: 0;
            padding: 0;
        }

        body, html {
            background-color: white;
            margin: 0;
            padding: 0;
        }

        .container-fluid {
            max-width: 100%;
            margin: 0;
            padding: 15px;
        }

        .card {
            box-shadow: none !important;
            border: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .card-header {
            display: none !important;
        }

        .card-body {
            padding: 0 !important;
        }

        .card-footer {
            display: none !important;
        }

        .table-responsive {
            overflow: visible !important;
            display: block !important;
        }

        .py-4 {
            padding: 6px 0 !important;
        }

        .mb-4 {
            margin-bottom: 6px !important;
        }

        .mb-2 {
            margin-bottom: 3px !important;
        }

        .display-4 {
            font-size: 22px !important;
            margin-bottom: 4px !important;
        }

        .lead {
            font-size: 11px !important;
            margin-bottom: 2px !important;
        }

        p {
            font-size: 11px !important;
        }

        .report-table {
            font-size: 11px !important;
            width: 100%;
            border-collapse: collapse !important;
            margin: 10px 0;
            display: table !important;
        }

        .report-table tbody {
            display: table-row-group !important;
        }

        .report-table thead {
            display: table-header-group !important;
            break-after: avoid;
            break-inside: avoid;
        }

        thead {
            display: table-header-group !important;
            break-after: avoid;
            break-inside: avoid;
        }

        .report-table thead th {
            background-color: #333 !important;
            color: white !important;
            padding: 5px 4px !important;
            text-align: right;
            border: 1px solid #333 !important;
            font-weight: bold !important;
            font-size: 10px !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        th {
            background-color: #333 !important;
            color: white !important;
            padding: 5px 4px !important;
            text-align: right;
            border: 1px solid #333 !important;
            font-weight: bold;
            font-size: 10px;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        td {
            padding: 4px 3px !important;
            border: 1px solid #ddd;
            text-align: right;
        }

        td:first-child,
        th:first-child {
            text-align: center;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:nth-child(odd) {
            background-color: white;
        }

        .total-row {
            background-color: #333 !important;
            color: white !important;
            font-weight: bold;
        }

        .total-row td {
            border: 1px solid #333 !important;
            color: white !important;
        }

        .badge {
            background-color: transparent !important;
            color: black !important;
            border: 1px solid #999;
            padding: 2px 3px;
            font-size: 9px;
        }

        tr {
            page-break-inside: avoid;
        }

        @page {
            size: A4 landscape;
            margin: 8mm;
        }
    }

    /* Hierarchical Indentation */
    .category-level-0 { margin-left: 0; }
    .category-level-1 { margin-left: 30px; }
    .category-level-2 { margin-left: 60px; }
    .category-level-3 { margin-left: 90px; }

    /* Hover Effects */
    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.1);
    }

    .total-row {
        border-top: 3px solid #fff;
    }
</style>

@endsection
