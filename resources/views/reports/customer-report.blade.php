@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-4">
                    <h1 class="display-4 font-weight-bold text-primary mb-2">
                        <i class="fas fa-user mr-2"></i>{{ __('messages.customer_report') }}
                    </h1>
                    <p class="lead text-muted">{{ __('messages.search_customer_details') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-search mr-2"></i>{{ __('messages.search_customer') }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group mb-0">
                        <label for="customerSearch" class="font-weight-bold mb-2">
                            {{ __('messages.customer_name_email_phone') }}:
                        </label>
                        <div class="input-group input-group-lg">
                            <input type="text"
                                   id="customerSearch"
                                   class="form-control"
                                   placeholder="{{ __('messages.type_customer_name_email_phone') }}"
                                   autocomplete="off">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="applyBtn" disabled>
                                    <i class="fas fa-check mr-2"></i>{{ __('messages.Apply') }}
                                </button>
                                <button class="btn btn-outline-secondary" type="button" id="resetBtn">
                                    <i class="fas fa-redo mr-2"></i>{{ __('messages.Reset') }}
                                </button>
                            </div>
                        </div>

                        <!-- Search Results Dropdown -->
                        <div id="searchResults" class="list-group mt-2" style="display: none; position: absolute; z-index: 1000; width: 100%; max-width: 400px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div id="loadingSpinner" style="display: none;" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">{{ __('messages.Loading') }}...</span>
        </div>
        <p class="text-muted mt-2">{{ __('messages.Loading') }}...</p>
    </div>

    <!-- Results Section (hidden until customer is selected) -->
    <div id="resultsSection" style="display: none;">
        <!-- Customer Info Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-user-circle mr-2"></i>
                            <span id="customerNameDisplay"></span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>{{ __('messages.Email') }}:</strong> <span id="customerEmail"></span></p>
                                <p class="mb-2"><strong>{{ __('messages.Phone') }}:</strong> <span id="customerPhone"></span></p>
                                <p class="mb-0"><strong>{{ __('messages.Location') }}:</strong> <span id="customerLocation"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>{{ __('messages.Member_Since') }}:</strong> <span id="customerJoinDate"></span></p>
                                <p class="mb-0"><strong>{{ __('messages.last_order_date') }}:</strong> <span id="customerLastOrder"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card border-left-primary shadow-sm h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    {{ __('messages.total_orders') }}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <span id="totalOrders">0</span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-2x text-primary"></i>
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
                                    {{ __('messages.total_spent') }}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <span id="totalSpent">0</span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-2x text-success"></i>
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
                                    {{ __('messages.average_order_value') }}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <span id="avgOrderValue">0</span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-bar fa-2x text-info"></i>
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
                                    {{ __('messages.total_discount') }}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <span id="totalDiscount">0</span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-tag fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-gradient-dark text-white">
                        <h5 class="mb-0"><i class="fas fa-list mr-2"></i>{{ __('messages.purchase_history') }}</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mb-0 table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('messages.Order_ID') }}</th>
                                        <th>{{ __('messages.order_date') }}</th>
                                        <th class="text-center">{{ __('messages.Status') }}</th>
                                        <th class="text-right">{{ __('messages.total_price') }}</th>
                                        <th class="text-right">{{ __('messages.total_discount') }}</th>
                                        <th class="text-right">{{ __('messages.tax_value') }}</th>
                                        <th class="text-right">{{ __('messages.delivery_fee') }}</th>
                                        <th class="text-center">{{ __('messages.payment_status') }}</th>
                                        <th class="text-center">{{ __('messages.Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="ordersTableBody">
                                    <tr>
                                        <td colspan="8" class="text-center py-3 text-muted">
                                            {{ __('messages.No_data') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-gradient-dark text-white">
                        <h5 class="mb-0"><i class="fas fa-box mr-2"></i>{{ __('messages.purchased_products') }}</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mb-0 table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>{{ __('messages.Product_Name') }}</th>
                                        <th class="text-center">{{ __('messages.Quantity') }}</th>
                                        <th class="text-right">{{ __('messages.total_price') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="productsTableBody">
                                    <tr>
                                        <td colspan="3" class="text-center py-3 text-muted">
                                            {{ __('messages.No_data') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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

    .list-group {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.1);
    }
</style>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('customerSearch');
    const searchResults = document.getElementById('searchResults');
    const applyBtn = document.getElementById('applyBtn');
    const resetBtn = document.getElementById('resetBtn');
    const resultsSection = document.getElementById('resultsSection');
    const loadingSpinner = document.getElementById('loadingSpinner');
    let selectedCustomerId = null;

    // Live search
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.trim();

        if (searchTerm.length < 2) {
            searchResults.style.display = 'none';
            applyBtn.disabled = true;
            selectedCustomerId = null;
            return;
        }

        // Fetch search results
        fetch('{{ route("customer-report.search") }}?search=' + encodeURIComponent(searchTerm))
            .then(response => response.json())
            .then(data => {
                displaySearchResults(data);
            })
            .catch(error => {
                console.error('Error:', error);
                searchResults.innerHTML = '<div class="alert alert-danger">Error loading results</div>';
            });
    });

    // Display search results
    function displaySearchResults(customers) {
        if (customers.length === 0) {
            searchResults.innerHTML = '<div class="list-group-item text-center text-muted">No customers found</div>';
            searchResults.style.display = 'block';
            applyBtn.disabled = true;
            return;
        }

        searchResults.innerHTML = '';
        customers.forEach(customer => {
            const item = document.createElement('a');
            item.href = '#';
            item.className = 'list-group-item list-group-item-action';
            item.innerHTML = `
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1">${customer.name}</h6>
                </div>
                <p class="mb-1"><small class="text-muted">${customer.email}</small></p>
                <p class="mb-0"><small class="text-muted">${customer.phone || 'N/A'}</small></p>
            `;

            item.addEventListener('click', function(e) {
                e.preventDefault();
                selectedCustomerId = customer.id;
                searchInput.value = customer.name;
                searchResults.style.display = 'none';
                applyBtn.disabled = false;
            });

            searchResults.appendChild(item);
        });

        searchResults.style.display = 'block';
    }

    // Apply button
    applyBtn.addEventListener('click', function() {
        if (!selectedCustomerId) {
            alert('Please select a customer');
            return;
        }

        loadingSpinner.style.display = 'block';
        resultsSection.style.display = 'none';

        // Fetch customer statistics
        fetch(`/ar/admin/customer-report/customer/${selectedCustomerId}`)
            .then(response => response.json())
            .then(data => {
                displayCustomerStats(data);
                loadingSpinner.style.display = 'none';
                resultsSection.style.display = 'block';
                searchResults.style.display = 'none';
            })
            .catch(error => {
                console.error('Error:', error);
                loadingSpinner.style.display = 'none';
                alert('Error loading customer data');
            });
    });

    // Display customer statistics
    function displayCustomerStats(data) {
        const customer = data.customer;
        const stats = data.stats;
        const orders = data.orders;
        const products = data.products;

        // Customer info
        document.getElementById('customerNameDisplay').textContent = customer.name;
        document.getElementById('customerEmail').textContent = customer.email;
        document.getElementById('customerPhone').textContent = customer.phone || 'N/A';
        document.getElementById('customerLocation').textContent = (customer.city || 'N/A') + ', ' + (customer.country || 'N/A');
        document.getElementById('customerJoinDate').textContent = customer.created_at;
        document.getElementById('customerLastOrder').textContent = stats.last_order_date;

        // Statistics
        document.getElementById('totalOrders').textContent = stats.total_orders;
        document.getElementById('totalSpent').textContent = parseFloat(stats.total_spent).toFixed(2);
        document.getElementById('avgOrderValue').textContent = parseFloat(stats.average_order_value).toFixed(2);
        document.getElementById('totalDiscount').textContent = parseFloat(stats.total_discount).toFixed(2);

        // Orders table
        const ordersTableBody = document.getElementById('ordersTableBody');
        ordersTableBody.innerHTML = '';
        const ordersShowBaseUrl = `{{ url('/en/admin/orders') }}`;

        if (orders.length === 0) {
            ordersTableBody.innerHTML = '<tr><td colspan="8" class="text-center py-3 text-muted">No orders</td></tr>';
        } else {
            orders.forEach(order => {
                const statusColor = getStatusColor(order.status_code);
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${order.number}</td>
                    <td>${order.date}</td>
                    <td class="text-center"><span class="badge ${statusColor}">${order.status}</span></td>
                    <td class="text-right">${parseFloat(order.total).toFixed(2)}</td>
                    <td class="text-right">${parseFloat(order.discount).toFixed(2)}</td>
                    <td class="text-right">${parseFloat(order.tax).toFixed(2)}</td>
                    <td class="text-right">${parseFloat(order.delivery_fee).toFixed(2)}</td>
                    <td class="text-center"><span class="badge badge-${order.payment_status === 'Paid' ? 'success' : 'warning'}">${order.payment_status}</span></td>
                    <td class="text-center"><a href="${ordersShowBaseUrl}/${order.id}" class="btn btn-sm btn-info" title="View Order"><i class="fas fa-eye"></i></a></td>
                `;
                ordersTableBody.appendChild(row);
            });
        }

        // Products table
        const productsTableBody = document.getElementById('productsTableBody');
        productsTableBody.innerHTML = '';

        if (products.length === 0) {
            productsTableBody.innerHTML = '<tr><td colspan="4" class="text-center py-3 text-muted">No products</td></tr>';
        } else {
            products.forEach((product, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="text-center">${index + 1}</td>
                    <td>${product.name}</td>
                    <td class="text-center">${product.quantity}</td>
                    <td class="text-right">${parseFloat(product.total_price).toFixed(2)}</td>
                `;
                productsTableBody.appendChild(row);
            });
        }
    }

    // Get status badge color
    function getStatusColor(statusCode) {
        const colors = {
            1: 'badge-warning',      // Pending
            2: 'badge-info',         // Accepted
            3: 'badge-primary',      // OnTheWay
            4: 'badge-success',      // Delivered
            5: 'badge-danger',       // Canceled
            6: 'badge-dark'          // Refund
        };
        return colors[statusCode] || 'badge-secondary';
    }

    // Reset button
    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        searchResults.style.display = 'none';
        resultsSection.style.display = 'none';
        applyBtn.disabled = true;
        selectedCustomerId = null;
    });

    // Hide search results when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target !== searchInput && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
});
</script>
@endsection
