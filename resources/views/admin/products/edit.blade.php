@extends('layouts.admin')
@section('title')
{{ __('messages.Edit') }} {{ __('messages.products') }}
@endsection

@section('contentheaderlink')
<a href="{{ route('products.index') }}"> {{ __('messages.products') }} </a>
@endsection

@section('contentheaderactive')
{{ __('messages.Edit') }}
@endsection


@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> {{ __('messages.Edit') }} {{ __('messages.products') }} </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">

        <form action="{{ route('products.update',$data['id']) }}" method="post" enctype='multipart/form-data'>
            <div class="row">
                @csrf
                @method('PUT')

                <input type="hidden" name="page" value="{{ request('page') }}">

                <!-- Product Type Field -->
                <div class="form-group col-md-6">
                    <label for="product_type"> {{ __('messages.product_type') }}</label>
                    <select class="form-control" name="product_type" id="product_type">
                        <option value="3" {{ ($data->product_type ?? 3) == 3 ? 'selected' : '' }}>Both (Retail & Wholesale)</option>
                        <option value="1" {{ ($data->product_type ?? 3) == 1 ? 'selected' : '' }}>Retail Only</option>
                        <option value="2" {{ ($data->product_type ?? 3) == 2 ? 'selected' : '' }}>Wholesale Only</option>
                    </select>
                    @error('product_type')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="brand_id">Brand</label>
                    <select class="form-control" name="brand" id="brand_id">
                        <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ $brand->id == $data->brand_id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('brand')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="category_id">Parent Category</label>
                    <select class="form-control" name="category" id="category_id">
                        <option value="">Select Parent Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $category->id == $data->category_id ? 'selected' : '' }}>
                            {{ $category->name_en }}
                        </option>
                        @endforeach
                    </select>
                    @error('category')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="unit_id">{{ __('messages.unit_for_user') }}</label>
                    <select class="form-control" name="unit" id="unit_id">
                        <option value="">Select Unit</option>
                        @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ $unit->id == $data->unit_id ? 'selected' : '' }}>
                            {{ $unit->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('unit')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="number"> {{ __('messages.number') }}</label>
                    <input name="number" id="number" class="form-control"
                        value="{{ old('number', $data->number) }}">
                    @error('number')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="barcode"> {{ __('messages.barcode') }}</label>
                    <input name="barcode" id="barcode" class="form-control"
                        value="{{ old('barcode', $data->barcode) }}">
                    @error('barcode')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="points"> {{ __('messages.points') }}</label>
                    <input name="points" id="points" class="form-control"
                        value="{{ old('points', $data->points) }}">
                    @error('points')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="name_ar"> {{ __('messages.Name_ar') }}</label>
                    <input name="name_ar" id="name_ar" class="form-control"
                        value="{{ old('name_ar', $data->name_ar) }}">
                    @error('name_ar')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="name_en"> {{ __('messages.Name_en') }}</label>
                    <input name="name_en" id="name_en" class="form-control"
                        value="{{ old('name_en', $data->name_en) }}">
                    @error('name_en')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="description_en"> {{ __('messages.description_en') }}</label>
                    <textarea name="description_en" id="description_en" class="form-control" rows="8">{{ old('description_en', $data->description_en) }}</textarea>
                    @error('description_en')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="description_ar"> {{ __('messages.description_ar') }}</label>
                    <textarea name="description_ar" id="description_ar" class="form-control" rows="8">{{ old('description_ar', $data->description_ar) }}</textarea>
                    @error('description_ar')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Modified Tax Section -->
                @php
                    $hasTax = $data->tax ? '1' : '0';
                    $selectedTaxId = null;
                    if ($data->tax) {
                        foreach($taxes as $tax) {
                            if ($tax->value == $data->tax) {
                                $selectedTaxId = $tax->id;
                                break;
                            }
                        }
                    }
                @endphp

                <div class="form-group col-md-6">
                    <label for="has_tax">{{ __('messages.has_tax') }}</label>
                    <select name="has_tax" id="has_tax" class="form-control">
                        <option value="0" {{ $hasTax == '0' ? 'selected' : '' }}>No</option>
                        <option value="1" {{ $hasTax == '1' ? 'selected' : '' }}>Yes</option>
                    </select>
                    @error('has_tax')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6" id="tax_dropdown" style="{{ $hasTax == '1' ? 'display: block;' : 'display: none;' }}">
                    <label for="tax_id">{{ __('messages.select_tax') }}</label>
                    <select name="tax_id" id="tax_id" class="form-control">
                        <option value="">Select Tax</option>
                        @foreach($taxes as $tax)
                        <option value="{{ $tax->id }}" {{ $selectedTaxId == $tax->id ? 'selected' : '' }}>
                            {{ $tax->name }} ({{ $tax->value }}%)
                        </option>
                        @endforeach
                    </select>
                    @error('tax_id')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Modified CRV Section -->
                @php
                    $hasCrv = $data->crv ? '1' : '0';
                    $selectedCrvId = null;
                    if ($data->crv) {
                        foreach($crvs as $crv) {
                            if ($crv->value == $data->crv) {
                                $selectedCrvId = $crv->id;
                                break;
                            }
                        }
                    }
                @endphp

                <div class="form-group col-md-6">
                    <label for="has_crv">{{ __('messages.has_crv') }}</label>
                    <select name="has_crv" id="has_crv" class="form-control">
                        <option value="0" {{ $hasCrv == '0' ? 'selected' : '' }}>No</option>
                        <option value="1" {{ $hasCrv == '1' ? 'selected' : '' }}>Yes</option>
                    </select>
                    @error('has_crv')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6" id="crv_dropdown" style="{{ $hasCrv == '1' ? 'display: block;' : 'display: none;' }}">
                    <label for="crv_id">{{ __('messages.select_crv') }}</label>
                    <select name="crv_id" id="crv_id" class="form-control">
                        <option value="">Select CRV</option>
                        @foreach($crvs as $crv)
                        <option value="{{ $crv->id }}" {{ $selectedCrvId == $crv->id ? 'selected' : '' }}>
                            {{ $crv->name }} ({{ $crv->value }})
                        </option>
                        @endforeach
                    </select>
                    @error('crv_id')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Retail Fields - Hidden/Shown based on product type -->
                <div class="form-group col-md-6" id="retail_price_field">
                    <label for="selling_price_for_user">{{ __('messages.selling_price_for_user') }}</label>
                    <input name="selling_price_for_user" id="selling_price_for_user" class="form-control"
                        value="{{ old('selling_price_for_user', $data->selling_price_for_user) }}">
                    @error('selling_price_for_user')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

            

                <div class="form-group col-md-6">
                    <label for="status"> {{ __('messages.Status') }}</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">Select</option>
                        <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Active</option>
                        <option value="2" {{ $data->status == 2 ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="in_stock"> {{ __('messages.in_stock') }}</label>
                    <select name="in_stock" id="in_stock" class="form-control">
                        <option value="">Select</option>
                        <option value="1" {{ $data->in_stock == 1 ? 'selected' : '' }}>Yes</option>
                        <option value="2" {{ $data->in_stock == 2 ? 'selected' : '' }}>No</option>
                    </select>
                    @error('in_stock')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <div class="form-group">
                        @if($data->productImages->count() > 0)
                        @foreach($data->productImages as $image)
                        <img src="{{ asset('assets/admin/uploads/' . $image->photo) }}" alt="Product Image"
                            height="50px" width="50px">
                        @endforeach
                        @else
                        <p>No images available for this product.</p>
                        @endif
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label>Product Images</label>
                    <input type="file" name="photo[]" class="form-control" multiple>
                </div>

            </div>

            <!-- Wholesale Units Tab - Only show if product type is wholesale or both -->
            <div id="wholesale_units_section">
                <ul class="nav nav-tabs" id="productTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab1-tab" data-bs-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">{{ __('messages.another_units') }}</a>
                    </li>
                </ul>

                <div class="tab-content" id="productTabContent">
                    <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                        <div id="product-units-container" class="mt-3">
                            @foreach ($data->units as $productUnit)
                                <div class="row product-unit">
                                    <!-- Unit Selection -->
                                    <div class="form-group col-md-3">
                                        <label for="unit">{{ __('messages.unit_for_wholeSale') }}</label>
                                        <select name="units[]" class="form-control">
                                            <option value="">Select Unit</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}" {{ $productUnit->id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Barcode Input -->
                                    <div class="form-group col-md-3">
                                        <label for="barcode">{{ __('messages.barcode') }}</label>
                                        <input type="number" class="form-control" name="barcodes[]" value="{{ $productUnit->pivot->barcode }}">
                                    </div>

                                    <!-- Releation Input -->
                                    <div class="form-group col-md-3">
                                        <label for="releation">{{ __('messages.releation') }}</label>
                                        <input type="number" class="form-control" name="releations[]" value="{{ $productUnit->pivot->releation }}">
                                    </div>

                                    <!-- Selling Price Input -->
                                    <div class="form-group col-md-3">
                                        <label for="selling_price">{{ __('messages.selling_price') }}</label>
                                        <input type="number" class="form-control" name="selling_prices[]" value="{{ $productUnit->pivot->selling_price }}" step="any">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-secondary mt-3" id="add-unit">Add Unit</button>
                    </div>
                </div>
            </div>

            <div class="form-group col-md-12 text-center">
                <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm">Update</button>
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-danger">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection

@section('script')
<script>
   // Function to toggle fields based on product type
function toggleProductTypeFields() {
    const productType = document.getElementById('product_type').value;
    const retailPriceField = document.getElementById('retail_price_field');
    const wholesaleUnitsSection = document.getElementById('wholesale_units_section');

    // Clear required attributes first
    document.getElementById('selling_price_for_user').removeAttribute('required');
    
    // Clear required attributes from wholesale units
    clearWholesaleUnitsRequired();

    if (productType === '1') { // Retail only
        retailPriceField.style.display = 'block';
        wholesaleUnitsSection.style.display = 'none';
        
        // Set required for retail fields
        document.getElementById('selling_price_for_user').setAttribute('required', 'required');
    } else if (productType === '2') { // Wholesale only
        retailPriceField.style.display = 'none';
        wholesaleUnitsSection.style.display = 'block';
        
        // Set required for wholesale units
        setWholesaleUnitsRequired();
    } else { // Both (3)
        retailPriceField.style.display = 'block';
        wholesaleUnitsSection.style.display = 'block';
        
        // Set required for retail and wholesale fields
        document.getElementById('selling_price_for_user').setAttribute('required', 'required');
        setWholesaleUnitsRequired();
    }
}

// Function to set wholesale units as required
function setWholesaleUnitsRequired() {
    const unitSelects = document.querySelectorAll('select[name="units[]"]');
    const relationInputs = document.querySelectorAll('input[name="releations[]"]');
    const priceInputs = document.querySelectorAll('input[name="selling_prices[]"]');

    // Make at least the first unit row required if it exists
    if (unitSelects.length > 0) {
        unitSelects[0].setAttribute('required', 'required');
        if (relationInputs[0]) relationInputs[0].setAttribute('required', 'required');
        if (priceInputs[0]) priceInputs[0].setAttribute('required', 'required');
    }
}

// Function to clear required attributes from wholesale units
function clearWholesaleUnitsRequired() {
    const unitSelects = document.querySelectorAll('select[name="units[]"]');
    const barcodeInputs = document.querySelectorAll('input[name="barcodes[]"]');
    const relationInputs = document.querySelectorAll('input[name="releations[]"]');
    const priceInputs = document.querySelectorAll('input[name="selling_prices[]"]');

    unitSelects.forEach(select => select.removeAttribute('required'));
    barcodeInputs.forEach(input => input.removeAttribute('required'));
    relationInputs.forEach(input => input.removeAttribute('required'));
    priceInputs.forEach(input => input.removeAttribute('required'));
}

// Function to add required attributes to new wholesale unit rows
function addRequiredToNewUnit(unitRow) {
    const productType = document.getElementById('product_type').value;
    
    if (productType === '2' || productType === '3') { // Wholesale only or Both
        const unitSelect = unitRow.querySelector('select[name="units[]"]');
        const relationInput = unitRow.querySelector('input[name="releations[]"]');
        const priceInput = unitRow.querySelector('input[name="selling_prices[]"]');
        
        if (unitSelect) unitSelect.setAttribute('required', 'required');
        if (relationInput) relationInput.setAttribute('required', 'required');
        if (priceInput) priceInput.setAttribute('required', 'required');
    }
}

// Function to validate wholesale units before form submission
function validateWholesaleUnits() {
    const productType = document.getElementById('product_type').value;
    
    if (productType === '2' || productType === '3') { // Wholesale only or Both
        const unitSelects = document.querySelectorAll('select[name="units[]"]');
        let hasValidUnit = false;
        
        unitSelects.forEach(select => {
            if (select.value !== '') {
                hasValidUnit = true;
            }
        });
        
        if (!hasValidUnit) {
            alert('Please add at least one wholesale unit.');
            return false;
        }
    }
    
    return true;
}

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
    toggleProductTypeFields();

    // Event listeners
    const productTypeElement = document.getElementById('product_type');
    const hasTaxElement = document.getElementById('has_tax');
    const hasCrvElement = document.getElementById('has_crv');

    if (productTypeElement) {
        productTypeElement.addEventListener('change', toggleProductTypeFields);
    }
    
    if (hasTaxElement) {
        hasTaxElement.addEventListener('change', toggleTaxDropdown);
    }
    
    if (hasCrvElement) {
        hasCrvElement.addEventListener('change', toggleCrvDropdown);
    }

    // Add form validation on submit
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateWholesaleUnits()) {
                e.preventDefault();
            }
        });
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
                <div class="form-group col-md-2">
                    <label for="unit">{{ __('messages.unit') }}</label>
                    <select name="units[]" class="form-control">
                        <option value="">Select Unit</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="barcode">{{ __('messages.barcode') }}</label>
                    <input type="number" class="form-control" name="barcodes[]">
                </div>
                <div class="form-group col-md-2">
                    <label for="releation">{{ __('messages.releation') }}</label>
                    <input type="number" class="form-control" name="releations[]">
                </div>
                <div class="form-group col-md-2">
                    <label for="selling_price">{{ __('messages.selling_price') }}</label>
                    <input type="number" class="form-control" name="selling_prices[]" step="any">
                </div>
                <div class="form-group col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-unit">Remove</button>
                </div>
            </div>`;
        
        const newUnitRow = $(unitTemplate);
        $('#product-units-container').append(newUnitRow);
        
        // Add required attributes to the new row if needed
        addRequiredToNewUnit(newUnitRow[0]);
    });

    // Handle remove unit button for both existing and new units
    $(document).on('click', '.remove-unit', function() {
        // Only allow removal if there's more than one unit row when wholesale is required
        const productType = document.getElementById('product_type').value;
        const unitRows = document.querySelectorAll('.product-unit');
        
        if ((productType === '2' || productType === '3') && unitRows.length <= 1) {
            alert('At least one wholesale unit is required.');
            return;
        }
        
        $(this).closest('.product-unit').remove();
    });
});
</script>
@endsection