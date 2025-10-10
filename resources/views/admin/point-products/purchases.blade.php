{{-- File: resources/views/point-products/admin-purchases.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="fas fa-shopping-cart"></i> Point Products Purchases</h1>
                <div>
                    <a href="{{ route('point-products.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-box"></i> Manage Products
                    </a>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Purchases</h5>
                            <h2>{{ number_format($totalPurchases) }}</h2>
                            <small>All time</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Items Sold</h5>
                            <h2>{{ number_format($totalItemsSold) }}</h2>
                            <small>Total quantity</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Points Revenue</h5>
                            <h2>{{ number_format($totalPointsSpent) }}</h2>
                            <small>Points collected</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center bg-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title">Unique Buyers</h5>
                            <h2>{{ number_format($uniqueBuyers) }}</h2>
                            <small>Different users</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('pointProducts.purchases') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="product_filter" class="form-label">Product</label>
                                <select class="form-control" id="product_filter" name="product">
                                    <option value="">All Products</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ request('product') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="date_from" class="form-label">From Date</label>
                                <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="date_to" class="form-label">To Date</label>
                                <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="user_search" class="form-label">Search User</label>
                                <input type="text" class="form-control" id="user_search" name="user_search" 
                                       placeholder="Name or email..." value="{{ request('user_search') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                        @if(request()->hasAny(['product', 'date_from', 'date_to', 'user_search']))
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <a href="{{ route('pointProducts.purchases') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-times"></i> Clear Filters
                                    </a>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Purchases Table -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-list"></i> Purchase History 
                        @if($purchases->total() > 0)
                            <span class="badge bg-secondary">{{ number_format($purchases->total()) }} records</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @if($purchases->isEmpty())
                        <div class="alert alert-info text-center">
                            <h4>No purchases found</h4>
                            <p>{{ request()->hasAny(['product', 'date_from', 'date_to', 'user_search']) ? 'Try adjusting your filters.' : 'No purchases have been made yet.' }}</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Purchase ID</th>
                                        <th>User</th>
                                        <th>Product</th>
                                        <th>Image</th>
                                        <th>Qty</th>
                                        <th>Points Spent</th>
                                        <th>Purchase Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($purchases as $purchase)
                                        <tr>
                                            <td>
                                                <strong>#{{ $purchase->id }}</strong>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $purchase->user->name ?? 'N/A' }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $purchase->user->email }}</small>
                                                    <br>
                                                    <span class="badge bg-info">{{ number_format($purchase->user->points) }} pts</span>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{{ $purchase->pointProduct->name }}</strong>
                                                @if($purchase->pointProduct->description)
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ Str::limit($purchase->pointProduct->description, 40) }}
                                                    </small>
                                                @endif
                                                <br>
                                                <span class="badge bg-primary">
                                                    {{ number_format($purchase->pointProduct->points_required) }} pts each
                                                </span>
                                            </td>
                                            <td>
                                                @if($purchase->pointProduct->image)
                                                    <img src="{{ asset('assets/admin/uploads/' . $purchase->pointProduct->image) }}" 
                                                         alt="{{ $purchase->pointProduct->name }}"
                                                         class="img-thumbnail"
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light border rounded d-flex align-items-center justify-content-center"
                                                         style="width: 60px; height: 60px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-success fs-6">{{ $purchase->quantity }}</span>
                                            </td>
                                            <td>
                                                <strong class="text-danger">
                                                    <i class="fas fa-coins"></i>
                                                    {{ number_format($purchase->points_spent) }}
                                                </strong>
                                            </td>
                                            <td>
                                                <small>
                                                    <strong>{{ $purchase->purchased_at->format('M d, Y') }}</strong><br>
                                                    <span class="text-muted">{{ $purchase->purchased_at->format('h:i A') }}</span>
                                                    <br>
                                                    <span class="badge bg-light text-dark">
                                                        {{ $purchase->purchased_at->diffForHumans() }}
                                                    </span>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                   
                                                    <a href="mailto:{{ $purchase->user->email }}" 
                                                       class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-envelope"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Purchase Detail Modal -->
                                        <div class="modal fade" id="purchaseModal{{ $purchase->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Purchase Details #{{ $purchase->id }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <h6><i class="fas fa-user"></i> Customer Information</h6>
                                                                <table class="table table-sm">
                                                                    <tr>
                                                                        <td><strong>Name:</strong></td>
                                                                        <td>{{ $purchase->user->name ?? 'N/A' }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Email:</strong></td>
                                                                        <td>{{ $purchase->user->email }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Current Points:</strong></td>
                                                                        <td>{{ number_format($purchase->user->points) }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Total Purchases:</strong></td>
                                                                        <td>{{ $purchase->user->pointProductPurchases->count() }}</td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h6><i class="fas fa-shopping-cart"></i> Purchase Information</h6>
                                                                <table class="table table-sm">
                                                                    <tr>
                                                                        <td><strong>Product:</strong></td>
                                                                        <td>{{ $purchase->pointProduct->name }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Points per Item:</strong></td>
                                                                        <td>{{ number_format($purchase->pointProduct->points_required) }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Quantity:</strong></td>
                                                                        <td>{{ $purchase->quantity }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Total Points:</strong></td>
                                                                        <td><strong>{{ number_format($purchase->points_spent) }}</strong></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><strong>Purchase Date:</strong></td>
                                                                        <td>{{ $purchase->purchased_at->format('F j, Y \a\t g:i A') }}</td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a href="mailto:{{ $purchase->user->email }}" class="btn btn-primary">
                                                            <i class="fas fa-envelope"></i> Email Customer
                                                        </a>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $purchases->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection