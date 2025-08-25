@extends('layouts.admin')
@section('title')
{{ __('messages.products') }}
@endsection


@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> {{ __('messages.Add_New') }} {{ __('messages.products') }}</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <form action="{{ route('products.store') }}" method="post" enctype='multipart/form-data'>
            <div class="row">
                @csrf
                <div class="form-group col-md-6">
                    <label for="category_id"> {{ __('messages.brands') }}</label>
                    <select class="form-control" name="brand" id="brand_id">
                        <option value="">Select Parent brand</option>
                        @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name}}</option>
                        @endforeach
                    </select>
                    @error('brand')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="category_id"> {{ __('messages.categories') }}</label>
                    <select class="form-control" name="category" id="category_id">
                        <option value="">Select Parent Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name_en }}</option>
                        @endforeach
                    </select>
                    @error('category')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="unit_id"> {{ __('messages.unit_for_user') }}</label>
                    <select class="form-control" name="unit" id="unit_id">
                        <option value="">Select Unit</option>
                        @foreach($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name_en }}</option>
                        @endforeach
                    </select>
                    @error('unit')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="number"> {{ __('messages.number') }}</label>
                    <input name="number" id="number" class="form-control" value="{{ old('number', $newNumber) }}">
                    @error('number')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="barcode"> {{ __('messages.barcode') }}</label>
                    <input name="barcode" id="barcode" class="form-control" value="{{ old('barcode') }}">
                    @error('barcode')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="points"> {{ __('messages.points') }}</label>
                    <input name="points" id="points" class="form-control" value="{{ old('points') }}">
                    @error('points')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="name_ar"> {{ __('messages.Name_ar') }}</label>
                    <input name="name_ar" id="name_ar" class="form-control" value="{{ old('name_ar') }}">
                    @error('name_ar')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="name_en"> {{ __('messages.Name_en') }}</label>
                    <input name="name_en" id="name_en" class="form-control" value="{{ old('name_en') }}">
                    @error('name_en')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="description_en"> {{ __('messages.description_en') }}</label>
                    <textarea name="description_en" id="description_en" class="form-control" rows="8">{{ old('description_en') }}</textarea>
                    @error('description_en')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="description_ar"> {{ __('messages.description_ar') }}</label>
                    <textarea name="description_ar" id="description_ar" class="form-control" rows="8">{{ old('description_ar') }}</textarea>
                    @error('description_ar')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Modified Tax Section -->
                <div class="form-group col-md-6">
                    <label for="has_tax"> {{ __('messages.has_tax') }}</label>
                    <select name="has_tax" id="has_tax" class="form-control">
                        <option value="0" @if(old('has_tax') == '0') selected @endif>No</option>
                        <option value="1" @if(old('has_tax') == '1') selected @endif>Yes</option>
                    </select>
                    @error('has_tax')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6" id="tax_dropdown" style="display: none;">
                    <label for="tax_id"> {{ __('messages.select_tax') }}</label>
                    <select name="tax_id" id="tax_id" class="form-control">
                        <option value="">Select Tax</option>
                        @foreach($taxes as $tax)
                        <option value="{{ $tax->id }}">{{ $tax->name }} ({{ $tax->value }}%)</option>
                        @endforeach
                    </select>
                    @error('tax_id')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Modified CRV Section -->
                <div class="form-group col-md-6">
                    <label for="has_crv"> {{ __('messages.has_crv') }}</label>
                    <select name="has_crv" id="has_crv" class="form-control">
                        <option value="0" @if(old('has_crv') == '0') selected @endif>No</option>
                        <option value="1" @if(old('has_crv') == '1') selected @endif>Yes</option>
                    </select>
                    @error('has_crv')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6" id="crv_dropdown" style="display: none;">
                    <label for="crv_id"> {{ __('messages.select_crv') }}</label>
                    <select name="crv_id" id="crv_id" class="form-control">
                        <option value="">Select CRV</option>
                        @foreach($crvs as $crv)
                        <option value="{{ $crv->id }}">{{ $crv->name }} ({{ $crv->value }})</option>
                        @endforeach
                    </select>
                    @error('crv_id')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="selling_price_for_user"> {{ __('messages.selling_price_for_user') }}</label>
                    <input name="selling_price_for_user" id="selling_price_for_user" class="form-control" value="{{ old('selling_price_for_user') }}">
                    @error('selling_price_for_user')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="min_order_for_user"> {{ __('messages.min_order_for_user') }}</label>
                    <input name="min_order_for_user" id="min_order_for_user" class="form-control" value="{{ old('min_order_for_user') }}">
                    @error('min_order_for_user')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="min_order_for_wholesale"> {{ __('messages.min_order_for_wholesale') }}</label>
                    <input name="min_order_for_wholesale" id="min_order_for_wholesale" class="form-control" value="{{ old('min_order_for_wholesale') }}">
                    @error('min_order_for_wholesale')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="status"> {{ __('messages.Status') }}</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">Select</option>
                        <option @if(old('status')==1 || old('status')=="") selected="selected" @endif value="1">Active</option>
                        <option @if(old('status')==2 and old('status')!="") selected="selected" @endif value="2">Inactive</option>
                    </select>
                    @error('status')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="in_stock"> {{ __('messages.in_stock') }}</label>
                    <select name="in_stock" id="in_stock" class="form-control">
                        <option value="">Select</option>
                        <option @if(old('in_stock')==1 || old('in_stock')=="") selected="selected" @endif value="1">Yes</option>
                        <option @if(old('in_stock')==2 and old('in_stock')!="") selected="selected" @endif value="2">No</option>
                    </select>
                    @error('in_stock')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

         

                <div class="form-group col-md-12">
                    <img src="" id="image-preview" alt="Selected Image" height="50px" width="50px" style="display: none;">
                    <button class="btn"> Photo</button>
                    <input type="file" id="Item_img" name="photo[]" class="form-control" onchange="previewImage()" multiple>
                    @error('photo')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <ul class="nav nav-tabs" id="productTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tab1-tab" data-bs-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">{{ __('messages.another_units') }}</a>
                </li>
            </ul>

            <div class="tab-content" id="productTabContent">
                <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                    <div id="product-units-container" class="mt-3">
                        <div class="row product-unit">
                            <div class="form-group col-md-3">
                                <label for="unit">{{ __('messages.unit_for_wholeSale') }}</label>
                                <select name="units[]" class="form-control" required>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="barcode">{{ __('messages.barcode') }}</label>
                                <input type="number" class="form-control" name="barcodes[]">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="releation">{{ __('messages.releation') }}</label>
                                <input type="number" class="form-control" name="releations[]">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="selling_price">{{ __('messages.selling_price') }}</label>
                                <input type="number" class="form-control" name="selling_prices[]" step="0.01" min="0">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary mt-3" id="add-unit">Add Unit</button>
                </div>
            </div>

            <div class="form-group col-md-12 text-center">
                <button id="do_add_item_cardd" type="submit" class="btn btn-primary">{{ __('messages.Submit') }}</button>
                <a href="{{ route('products.index') }}" class="btn btn-danger">{{ __('messages.Cancel') }}</a>
            </div>
        </form>
    </div>
</div>

@endsection
@section('script')
<script>
  

    // Function to toggle tax dropdown
    function toggleTaxDropdown() {
        const hasTaxElement = document.getElementById('has_tax');
        const taxDropdown = document.getElementById('tax_dropdown');

        if (hasTaxElement && taxDropdown) {
            if (hasTaxElement.value === '1') {
                taxDropdown.style.display = 'block';
            } else {
                taxDropdown.style.display = 'none';
            }
        }
    }

    // Function to toggle crv dropdown
    function toggleCrvDropdown() {
        const hasCrvElement = document.getElementById('has_crv');
        const crvDropdown = document.getElementById('crv_dropdown');

        if (hasCrvElement && crvDropdown) {
            if (hasCrvElement.value === '1') {
                crvDropdown.style.display = 'block';
            } else {
                crvDropdown.style.display = 'none';
            }
        }
    }

    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Initial state on page load
        toggleTaxDropdown();
        toggleCrvDropdown();

        // Event listeners
        const hasTaxElement = document.getElementById('has_tax');
        const hasCrvElement = document.getElementById('has_crv');

        
        if (hasTaxElement) {
            hasTaxElement.addEventListener('change', toggleTaxDropdown);
        }
        
        if (hasCrvElement) {
            hasCrvElement.addEventListener('change', toggleCrvDropdown);
        }
    });

    $(document).ready(function() {
        $('#productTab a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        $('#add-unit').on('click', function() {
            const unitTemplate = `
                <div class="row product-unit">
                    <div class="form-group col-md-3">
                        <label for="unit">{{ __('messages.unit') }}</label>
                        <select name="units[]" class="form-control" required>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name_en }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="barcode">{{ __('messages.barcode') }}</label>
                        <input type="number" class="form-control" name="barcodes[]">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="releation">{{ __('messages.releation') }}</label>
                        <input type="number" class="form-control" name="releations[]">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="selling_price">{{ __('messages.selling_price') }}</label>
                        <input type="number" class="form-control" name="selling_prices[]" step="0.01" min="0">
                    </div>
                </div>`;
            $('#product-units-container').append(unitTemplate);
        });
    });
</script>
@endsection
