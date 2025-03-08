@extends('layouts.admin')
@section('title')
{{ __('messages.products') }}
@endsection

@section('css')
<style>
    /* Style for the "plus" button */
    #add-variation {
        display: block;
        margin-top: 10px;
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
    }

    /* Style for the variation fields container */
    #variationFields {
        border: 1px solid #ccc;
        padding: 10px;
        margin-top: 10px;
    }

    /* Style for individual variation fields */
    .variation {
        border: 1px solid #ccc;
        padding: 10px;
        margin-top: 10px;
    }
</style>
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
                    <input name="number" id="number" class="form-control" value="{{ old('number') }}">
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

               

                <div class="form-group col-md-6">
                    <label for="tax"> {{ __('messages.tax') }} %</label>
                    <input name="tax" id="tax" class="form-control" value="{{ old('tax') }}">
                    @error('tax')
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

                <div class="form-group col-md-6">
                    <label for="has_variation"> {{ __('messages.has_variation') }}</label>
                    <select name="has_variation" id="has_variation" class="form-control">
                        <option value="">Select</option>
                        <option @if(old('has_variation')==1 || old('has_variation')=="") selected="selected" @endif value="1">Active</option>
                        <option @if(old('has_variation')==0 and old('has_variation')!="") selected="selected" @endif value="0">Inactive</option>
                    </select>
                    @error('has_variation')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div id="variationFields" class="form-group col-md-12">
                    <div class="variation row">
                        <div class="form-group col-md-4">
                            <input type="text" name="attributes[]" class="form-control" placeholder="Attributes">
                        </div>
                        <div class="form-group col-md-4">
                            <input type="text" name="variations[]" class="form-control" placeholder="Variations">
                        </div>
                        <div class="form-group col-md-4">
                            <input type="number" name="available_quantities[]" class="form-control" placeholder="Available Quantity">
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <button type="button" id="add-variation" class="btn btn-primary">Add Variation</button>
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
    // Function to toggle visibility of variation fields
    function toggleVariationFields() {
        const hasVariation = document.getElementById('has_variation').value;
        const variationFields = document.getElementById('variationFields');

        if (hasVariation === '1') {
            variationFields.style.display = 'block';
        } else {
            variationFields.style.display = 'none';
        }
    }

    // Initial state on page load
    toggleVariationFields();

    // Event listener to toggle fields when the selection changes
    document.getElementById('has_variation').addEventListener('change', toggleVariationFields);

    // Function to add new variation fields
    document.getElementById('add-variation').addEventListener('click', function () {
        const variationFields = document.getElementById('variationFields');
        const variation = document.querySelector('.variation');
        const clone = variation.cloneNode(true);
        variationFields.appendChild(clone);
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
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
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
                        <input type="number" class="form-control" name="selling_prices[]">
                    </div>
                </div>`;
            $('#product-units-container').append(unitTemplate);
        });
    });
</script>
@endsection
