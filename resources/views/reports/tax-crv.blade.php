@extends('layouts.admin')

@section('css')
<style>
.icon-placeholder {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: box-shadow 0.15s ease-in-out;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.1rem;
}

.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 300;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    color: #6c757d;
    font-size: 1.1rem;
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">{{ __('Tax & CRV Reports') }}</h1>
                <p class="page-subtitle">{{ __('US Government Compliance Reports for Sales Tax and California Redemption Value') }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-placeholder bg-primary text-white rounded-circle p-3 me-3">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">This Month Tax</h6>
                            <h4 class="mb-0" id="thisMonthTax">$0.00</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-placeholder bg-success text-white rounded-circle p-3 me-3">
                            <i class="fas fa-recycle"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">This Month CRV</h6>
                            <h4 class="mb-0" id="thisMonthCrv">$0.00</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-placeholder bg-warning text-white rounded-circle p-3 me-3">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Total Orders</h6>
                            <h4 class="mb-0" id="totalOrders">0</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-placeholder bg-info text-white rounded-circle p-3 me-3">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Due Date</h6>
                            <p class="mb-0" id="nextDueDate">{{ date('M d, Y', strtotime('next month')) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Section -->
    <div class="row">
        <!-- Sales Tax Reports -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-receipt me-2"></i>
                        {{ __('Sales Tax Reports') }}
                    </h4>
                </div>
                <div class="card-body">
                    <form id="taxReportForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tax_start_date">{{ __('Start Date') }}</label>
                                    <input type="date" class="form-control" id="tax_start_date" name="start_date" value="{{ date('Y-m-01') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tax_end_date">{{ __('End Date') }}</label>
                                    <input type="date" class="form-control" id="tax_end_date" name="end_date" value="{{ date('Y-m-t') }}" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" data-format="excel">
                                <i class="fas fa-download me-2"></i>
                                {{ __('Download Sales Tax Report (Excel)') }}
                            </button>
                            <button type="submit" class="btn btn-outline-primary" data-format="json">
                                <i class="fas fa-eye me-2"></i>
                                {{ __('View Tax Summary') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- CRV Reports -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-recycle me-2"></i>
                        {{ __('CRV Reports') }}
                    </h4>
                </div>
                <div class="card-body">
                    <form id="crvReportForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="crv_start_date">{{ __('Start Date') }}</label>
                                    <input type="date" class="form-control" id="crv_start_date" name="start_date" value="{{ date('Y-m-01') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="crv_end_date">{{ __('End Date') }}</label>
                                    <input type="date" class="form-control" id="crv_end_date" name="end_date" value="{{ date('Y-m-t') }}" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg" data-format="excel">
                                <i class="fas fa-download me-2"></i>
                                {{ __('Download CRV Report (Excel)') }}
                            </button>
                            <button type="submit" class="btn btn-outline-success" data-format="json">
                                <i class="fas fa-eye me-2"></i>
                                {{ __('View CRV Summary') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Combined & Monthly Reports -->
    <div class="row mt-4">
        <!-- Combined Report -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-file-contract me-2"></i>
                        {{ __('Combined Tax & CRV Report') }}
                    </h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">{{ __('Generate a comprehensive report including both sales tax and CRV data for government compliance.') }}</p>
                    
                    <form id="combinedReportForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="combined_start_date">{{ __('Start Date') }}</label>
                                    <input type="date" class="form-control" id="combined_start_date" name="start_date" value="{{ date('Y-m-01') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="combined_end_date">{{ __('End Date') }}</label>
                                    <input type="date" class="form-control" id="combined_end_date" name="end_date" value="{{ date('Y-m-t') }}" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning btn-lg text-dark">
                                <i class="fas fa-download me-2"></i>
                                {{ __('Download Combined Report (Excel)') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Monthly Summary -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>
                        {{ __('Monthly Tax Summary') }}
                    </h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">{{ __('Generate monthly tax summary for government filing purposes.') }}</p>
                    
                    <form id="monthlyReportForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="report_year">{{ __('Year') }}</label>
                                    <select class="form-control" id="report_year" name="year" required>
                                        @for($year = date('Y'); $year >= 2020; $year--)
                                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="report_month">{{ __('Month') }}</label>
                                    <select class="form-control" id="report_month" name="month" required>
                                        @for($month = 1; $month <= 12; $month++)
                                            <option value="{{ $month }}" {{ $month == date('n') ? 'selected' : '' }}>
                                                {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-info btn-lg" data-format="excel">
                                <i class="fas fa-download me-2"></i>
                                {{ __('Download Monthly Summary (Excel)') }}
                            </button>
                            <button type="submit" class="btn btn-outline-info" data-format="json">
                                <i class="fas fa-eye me-2"></i>
                                {{ __('View Monthly Summary') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Compliance Information -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ __('Compliance Information') }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <h5>{{ __('Sales Tax Compliance') }}</h5>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>{{ __('File monthly/quarterly returns') }}</li>
                                <li><i class="fas fa-check text-success me-2"></i>{{ __('Pay by 20th of following month') }}</li>
                                <li><i class="fas fa-check text-success me-2"></i>{{ __('Maintain records for 4 years') }}</li>
                                <li><i class="fas fa-check text-success me-2"></i>{{ __('Report all taxable sales') }}</li>
                            </ul>
                        </div>
                        <div class="col-lg-6">
                            <h5>{{ __('CRV Compliance') }}</h5>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-recycle text-success me-2"></i>{{ __('Monthly payments to CalRecycle') }}</li>
                                <li><i class="fas fa-recycle text-success me-2"></i>{{ __('Due by 15th of following month') }}</li>
                                <li><i class="fas fa-recycle text-success me-2"></i>{{ __('Track all beverage containers') }}</li>
                                <li><i class="fas fa-recycle text-success me-2"></i>{{ __('Maintain detailed records') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Results Modal -->
<div class="modal fade" id="resultsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Report Results') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="resultsContent"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    console.log('Tax reports page loaded');
    
    // Load dashboard stats on page load
    loadDashboardStats();

    // Tax Report Form
    $('#taxReportForm').on('submit', function(e) {
        e.preventDefault();
        
        // Get the clicked button and its format
        const clickedButton = $(document.activeElement);
        const format = clickedButton.data('format') || 'json';
        
        console.log('Tax form submitted with format:', format);
        
        const formData = new FormData(this);
        formData.append('format', format);
        
        if (format === 'excel') {
            downloadReport('{{ route("admin.tax-crv.sales-tax") }}', formData, clickedButton);
        } else {
            viewReport('{{ route("admin.tax-crv.sales-tax") }}', formData, 'Sales Tax Summary', clickedButton);
        }
    });

    // CRV Report Form
    $('#crvReportForm').on('submit', function(e) {
        e.preventDefault();
        
        const clickedButton = $(document.activeElement);
        const format = clickedButton.data('format') || 'json';
        
        console.log('CRV form submitted with format:', format);
        
        const formData = new FormData(this);
        formData.append('format', format);
        
        if (format === 'excel') {
            downloadReport('{{ route("admin.tax-crv.crv-report") }}', formData, clickedButton);
        } else {
            viewReport('{{ route("admin.tax-crv.crv-report") }}', formData, 'CRV Summary', clickedButton);
        }
    });


    $('#combinedReportForm').on('submit', function(e) {
    e.preventDefault();
    
    const clickedButton = $(document.activeElement);
    const formData = new FormData(this);
    
    console.log('Combined form submitted');
    
    // Combined report always downloads Excel, so use downloadReport function
    downloadReport('{{ route("admin.tax-crv.combined") }}', formData, clickedButton);
});



    // Monthly Report Form
    $('#monthlyReportForm').on('submit', function(e) {
        e.preventDefault();
        
        const clickedButton = $(document.activeElement);
        const format = clickedButton.data('format') || 'json';
        
        console.log('Monthly form submitted with format:', format);
        
        const formData = new FormData(this);
        formData.append('format', format);
        
        if (format === 'excel') {
            downloadReport('{{ route("admin.tax-crv.monthly-summary") }}', formData, clickedButton);
        } else {
            viewReport('{{ route("admin.tax-crv.monthly-summary") }}', formData, 'Monthly Tax Summary', clickedButton);
        }
    });

    // Also update your downloadReport function to handle the blob error better:
    function downloadReport(url, formData, button) {
        const originalText = button.html();
        
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Generating...');
        
        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhrFields: {
                responseType: 'blob'
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data, status, xhr) {
                console.log('Download successful, content type:', xhr.getResponseHeader('Content-Type'));
                
                // Check if response is actually a blob (file) or JSON (error)
                const contentType = xhr.getResponseHeader('Content-Type');
                
                if (contentType && (contentType.includes('application/json') || contentType.includes('text/plain'))) {
                    // This is probably an error response, not a file
                    const reader = new FileReader();
                    reader.onload = function() {
                        try {
                            const errorData = JSON.parse(reader.result);
                            showAlert('Error: ' + (errorData.error || errorData.message || 'Unknown error'), 'error');
                        } catch (e) {
                            showAlert('Server returned an error: ' + reader.result, 'error');
                        }
                    };
                    reader.readAsText(data);
                    return;
                }
                
                // Handle successful file download
                const blob = new Blob([data], { 
                    type: contentType || 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' 
                });
                const downloadUrl = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = downloadUrl;
                
                // Get filename from header or create default
                const disposition = xhr.getResponseHeader('Content-Disposition');
                let filename = 'report.xlsx';
                if (disposition && disposition.indexOf('attachment') !== -1) {
                    const matches = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/.exec(disposition);
                    if (matches != null && matches[1]) {
                        filename = matches[1].replace(/['"]/g, '');
                    }
                }
                
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(downloadUrl);
                
                showAlert('Report downloaded successfully!', 'success');
            },
            error: function(xhr, status, error) {
                console.error('Download failed:', xhr);
                
                let errorMessage = 'Error generating report';
                
                // Try to read error from blob response
                if (xhr.responseText) {
                    try {
                        const errorData = JSON.parse(xhr.responseText);
                        errorMessage = errorData.error || errorData.message || errorMessage;
                    } catch (e) {
                        errorMessage = xhr.responseText || errorMessage;
                    }
                } else if (xhr.response) {
                    // Handle blob error response
                    const reader = new FileReader();
                    reader.onload = function() {
                        try {
                            const errorData = JSON.parse(reader.result);
                            showAlert('Error: ' + (errorData.error || errorData.message || 'Unknown error'), 'error');
                        } catch (e) {
                            showAlert('Server error: ' + reader.result, 'error');
                        }
                    };
                    reader.readAsText(xhr.response);
                    return;
                }
                
                showAlert(errorMessage, 'error');
            },
            complete: function() {
                button.prop('disabled', false).html(originalText);
            }
        });
    }

    function viewReport(url, formData, title, button) {
        const originalText = button.html();
        
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Loading...');
        
        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                console.log('Report data loaded:', data);
                displayResults(data, title);
            },
            error: function(xhr) {
                console.error('View report failed:', xhr);
                let errorMessage = 'Error loading report';
                try {
                    const errorData = JSON.parse(xhr.responseText);
                    errorMessage = errorData.error || errorData.message || errorMessage;
                } catch (e) {
                    errorMessage = xhr.responseText || errorMessage;
                }
                showAlert(errorMessage, 'error');
            },
            complete: function() {
                button.prop('disabled', false).html(originalText);
            }
        });
    }

    function displayResults(data, title) {
        $('#resultsModal .modal-title').text(title);
        let html = '';
        
        if (data.summary) {
            html += '<h5>Summary</h5>';
            html += '<div class="row">';
            
            if (data.summary.totals) {
                Object.entries(data.summary.totals).forEach(([key, value]) => {
                    const label = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    html += `<div class="col-md-6 mb-2">
                        <strong>${label}:</strong> ${typeof value === 'number' ? '$' + value.toLocaleString() : value}
                    </div>`;
                });
            }
            
            html += '</div>';
            
            // Show some transaction data if available
            if (data.transactions && data.transactions.length > 0) {
                html += '<hr><h6>Sample Transactions (First 5)</h6>';
                html += '<div class="table-responsive">';
                html += '<table class="table table-striped table-sm">';
                html += '<thead><tr><th>Order</th><th>Date</th><th>Customer</th><th>Product</th><th>Amount</th><th>Tax</th></tr></thead>';
                html += '<tbody>';
                
                data.transactions.slice(0, 5).forEach(transaction => {
                    html += `<tr>
                        <td>${transaction.order_number || 'N/A'}</td>
                        <td>${transaction.date || 'N/A'}</td>
                        <td>${transaction.customer_name || 'Guest'}</td>
                        <td>${transaction.product_name || 'N/A'}</td>
                        <td>$${transaction.sale_amount || 0}</td>
                        <td>$${transaction.tax_amount || 0}</td>
                    </tr>`;
                });
                
                html += '</tbody></table></div>';
                
                if (data.transactions.length > 5) {
                    html += `<p class="text-muted">Showing 5 of ${data.transactions.length} transactions</p>`;
                }
            }
        }
        
        $('#resultsContent').html(html);
        $('#resultsModal').modal('show');
    }

    function loadDashboardStats() {
        console.log('Loading dashboard statistics...');
        
        $.ajax({
            url: '{{ route("admin.tax-crv.dashboard-stats") }}',
            method: 'GET',
            success: function(data) {
                console.log('Dashboard stats loaded successfully:', data);
                
                // Update dashboard cards with real data
                $('#thisMonthTax').text('$' + (data.current_month_tax || 0).toLocaleString('en-US', {minimumFractionDigits: 2}));
                $('#thisMonthCrv').text('$' + (data.current_month_crv || 0).toLocaleString('en-US', {minimumFractionDigits: 2}));
                $('#totalOrders').text((data.total_orders || 0).toLocaleString());
                $('#nextDueDate').text(data.next_due_date || 'Unknown');
                
                // Show debug info in console if available
                if (data.debug) {
                    console.log('Debug information:', data.debug);
                    
                    // Show helpful alerts based on debug info
                    if (data.debug.total_orders_found === 0) {
                        console.warn('No orders found for current month:', data.debug.date_range);
                    } else if (data.debug.total_orders_found > 0 && data.current_month_tax === 0) {
                        console.warn('Orders found but no tax calculated. Check tax_value field.');
                    }
                }
                
                // Show tax due alert if significant amount
                if (data.tax_due_amount > 0) {
                    showAlert(`Tax Payment Due: $${data.tax_due_amount.toLocaleString('en-US', {minimumFractionDigits: 2})} due by ${data.next_due_date}`, 'warning');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading dashboard stats:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    error: error
                });
                
                showAlert('Error loading dashboard statistics. Please check the console for details.', 'error');
            }
        });
    }

    function showAlert(message, type) {
        const alertClass = type === 'error' ? 'alert-danger' : 
                          type === 'warning' ? 'alert-warning' : 'alert-success';
        
        const alert = `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
        
        $('.container-fluid').prepend(alert);
        
        setTimeout(() => {
            $('.alert').alert('close');
        }, 5000);
    }
});
</script>
@endsection