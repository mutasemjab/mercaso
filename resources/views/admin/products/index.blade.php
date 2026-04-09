@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products Management</h1>
        <div>
            @can('product-add')
                <a href="{{ route('products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Product
                </a>
            @endcan
        </div>
    </div>


    <!-- Search and Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('products.index') }}" method="GET">
                <div class="row">
                    <!-- Search -->
                    <div class="col-md-3 mb-2">
                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Search by name, number, or barcode..."
                               value="{{ request('search') }}">
                    </div>

                    <!-- Category -->
                    <div class="col-md-2 mb-2">
                        <select name="category_id" class="form-control">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name_ar }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="col-md-2 mb-2">
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Not Active</option>
                        </select>
                    </div>

                    <!-- In Stock -->
                    <div class="col-md-2 mb-2">
                        <select name="in_stock" class="form-control">
                            <option value="">All Stock</option>
                            <option value="1" {{ request('in_stock') == '1' ? 'selected' : '' }}>In Stock</option>
                            <option value="2" {{ request('in_stock') == '2' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="col-md-3 mb-2 d-flex">
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-search"></i> Search
                        </button>
                        @if(request()->hasAny(['search','category_id','status','in_stock']))
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Clear
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Products List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="10%">Image</th>
                            <th width="15%">Name</th>
                            <th width="10%">Category</th>
                            <th width="8%">Type</th>
                            <th width="8%">Price</th>
                            <th width="8%">Stock</th>
                            <th width="8%">Status</th>
                            <th width="10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $product)
                            <tr>
                                <td>{{ $data->firstItem() + $index }}</td>
                                <td>
                                    @if($product->productImages->count() > 0)
                                        <img src="{{ asset('assets/admin/uploads/'.$product->productImages->first()->photo) }}" 
                                             alt="{{ $product->name_ar }}" 
                                             class="img-thumbnail" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('assets/admin/img/no-image.png') }}" 
                                             alt="No Image" 
                                             class="img-thumbnail" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @endif
                                </td>
                           
                                <td>
                                    <strong>{{ $product->name_ar }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $product->name_en }}</small>
                                </td>
                                <td>
                                    @if($product->category)
                                        <span class="badge badge-info">{{ $product->category->name_ar }}</span>
                                    @else
                                        <span class="badge badge-secondary">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->product_type == 1)
                                        <span class="badge badge-primary">Retail</span>
                                    @elseif($product->product_type == 2)
                                        <span class="badge badge-warning">Wholesale</span>
                                    @else
                                        <span class="badge badge-success">Both</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->product_type == 1 || $product->product_type == 3)
                                        <strong>$ {{ number_format($product->selling_price_for_user, 2) }}</strong>
                                    @elseif($product->product_type == 2)
                                        @if($product->units->isNotEmpty() && $product->units->first()->pivot->selling_price)
                                            <strong>$ {{ number_format($product->units->first()->pivot->selling_price, 2) }}</strong>
                                            <br><small class="text-muted">Wholesale</small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($product->in_stock == 1)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i> In Stock
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times"></i> Out of Stock
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" 
                                               class="custom-control-input status-toggle" 
                                               id="status-{{ $product->id }}" 
                                               data-id="{{ $product->id }}"
                                               {{ $product->status == 1 ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="status-{{ $product->id }}">
                                            <span class="status-text-{{ $product->id }}">
                                                {{ $product->status == 1 ? 'Active' : 'Inactive' }}
                                            </span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @can('product-edit')
                                            <a href="{{ route('products.edit', ['product' => $product->id, 'page' => $data->currentPage()]) }}" 
                                               class="btn btn-sm btn-warning" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                        
                                        @can('product-delete')
                                            <form action="{{ route('products.destroy', $product->id) }}" 
                                                  method="POST" 
                                                  class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-danger" 
                                                        title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this product?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No products found.</p>
                                    @can('product-add')
                                        <a href="{{ route('products.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Add Your First Product
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($data->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of {{ $data->total() }} products
                    </div>
                    <div>
                        {{ $data->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Status toggle functionality
    $('.status-toggle').on('change', function() {
        const productId = $(this).data('id');
        const isChecked = $(this).is(':checked');
        const toggleSwitch = $(this);
        
        $.ajax({
           url: '{{ route("products.toggleStatus", ":id") }}'.replace(':id', productId),
            type: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    // Update status text
                    $(`.status-text-${productId}`).text(response.status_text);
   
                } else {
                    // Revert toggle if failed
                    toggleSwitch.prop('checked', !isChecked);
                }
            },
            error: function() {
                // Revert toggle on error
                toggleSwitch.prop('checked', !isChecked);
            }
        });
    });
    
});
</script>
@endpush


@endsection