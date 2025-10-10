{{-- File: resources/views/point-products/edit.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-edit"></i> Edit Point Product: {{ $pointProduct->name }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('point-products.update', $pointProduct) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Product Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name *</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $pointProduct->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4">{{ old('description', $pointProduct->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Points Required and Stock Row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="points_required" class="form-label">Points Required *</label>
                                    <input type="number" 
                                           class="form-control @error('points_required') is-invalid @enderror" 
                                           id="points_required" 
                                           name="points_required" 
                                           value="{{ old('points_required', $pointProduct->points_required) }}" 
                                           min="1" 
                                           required>
                                    @error('points_required')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock" class="form-label">Stock Quantity *</label>
                                    <input type="number" 
                                           class="form-control @error('stock') is-invalid @enderror" 
                                           id="stock" 
                                           name="stock" 
                                           value="{{ old('stock', $pointProduct->stock) }}" 
                                           min="0" 
                                           required>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Current Image -->
                        @if($pointProduct->image)
                            <div class="mb-3">
                                <label class="form-label">Current Image:</label>
                                <div>
                                    <img src="{{ asset('assets/admin/uploads/' . $pointProduct->image) }}" 
                                         alt="{{ $pointProduct->name }}" 
                                         class="img-thumbnail" 
                                         style="max-width: 200px; max-height: 200px;">
                                </div>
                            </div>
                        @endif

                        <!-- Product Image -->
                        <div class="mb-3">
                            <label for="image" class="form-label">
                                {{ $pointProduct->image ? 'Replace Image' : 'Product Image' }}
                            </label>
                            <input type="file" 
                                   class="form-control @error('image') is-invalid @enderror" 
                                   id="image" 
                                   name="image" 
                                   accept="image/*">
                            <div class="form-text">
                                {{ $pointProduct->image ? 'Leave empty to keep current image. ' : '' }}
                                Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB
                            </div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New Image Preview -->
                        <div class="mb-3" id="image-preview" style="display: none;">
                            <label class="form-label">New Image Preview:</label>
                            <div>
                                <img id="preview-img" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                            </div>
                        </div>

                        <!-- Active Status -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', $pointProduct->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <strong>Product is Active</strong>
                                    <small class="text-muted d-block">Users can purchase this product when active</small>
                                </label>
                            </div>
                        </div>

                        <!-- Product Stats -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-chart-line"></i> Product Statistics:</h6>
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <strong>{{ $pointProduct->purchases()->count() }}</strong>
                                            <small class="d-block text-muted">Total Purchases</small>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>{{ $pointProduct->purchases()->sum('quantity') }}</strong>
                                            <small class="d-block text-muted">Items Sold</small>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>{{ number_format($pointProduct->purchases()->sum('points_spent')) }}</strong>
                                            <small class="d-block text-muted">Points Earned</small>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>{{ $pointProduct->created_at->format('M d, Y') }}</strong>
                                            <small class="d-block text-muted">Created</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-save"></i> Update Product
                                </button>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('point-products.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-arrow-left"></i> Back to List
                                </a>
                            </div>
                            <div class="col-md-4">
                                <button type="button" 
                                        class="btn btn-danger w-100" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal">
                                    <i class="fas fa-trash"></i> Delete Product
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong>{{ $pointProduct->name }}</strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Warning:</strong> This action cannot be undone. All purchase history for this product will remain, but the product will be permanently deleted.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('point-products.destroy', $pointProduct) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete Product
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const previewContainer = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewContainer.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
        }
    });
});
</script>
@endsection