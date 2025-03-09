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
        <h3 class="card-title card_title_center"> {{ __('messages.Edit') }} {{ __('messages.products') }} </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">


        <form action="{{ route('products.update',$data['id']) }}" method="post" enctype='multipart/form-data'>
            <div class="row">
                @csrf
                @method('PUT')



                <div class="form-group col-md-6">
                    <label for="category_id">Brand</label>
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

                <div class="col-md-6">
                    <div class="form-group">
                        <label> {{ __('messages.number') }}</label>
                        <input name="number" id="number" class="form-control"
                            value="{{ old('number', $data->number) }}">
                        @error('number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> {{ __('messages.barcode') }}</label>
                        <input name="barcode" id="barcode" class="form-control"
                            value="{{ old('barcode', $data->barcode) }}">
                        @error('barcode')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> {{ __('messages.Name_ar') }}</label>
                        <input name="name_ar" id="name_ar" class="form-control"
                            value="{{ old('name_ar', $data->name_ar) }}">
                        @error('name_ar')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> {{ __('messages.Name_en') }}</label>
                        <input name="name_en" id="name_en" class="form-control"
                            value="{{ old('name_en', $data->name_en) }}">
                        @error('name_en')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> {{ __('messages.Name_fr') }}</label>
                        <input name="name_fr" id="name_fr" class="form-control"
                            value="{{ old('name_fr', $data->name_fr) }}">
                        @error('name_fr')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> {{ __('messages.description_en') }}</label>
                        <textarea name="description_en" id="description_en" class="form-control"
                            value="{{ old('description_en') }}" rows="8">{{$data->description_en}}</textarea>
                        @error('description_en')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label> {{ __('messages.description_ar') }}</label>
                        <textarea name="description_ar" id="description_ar" class="form-control"
                            value="{{ old('description_ar') }}" rows="8">{{$data->description_ar}}</textarea>
                        @error('description_ar')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label> {{ __('messages.description_fr') }}</label>
                        <textarea name="description_fr" id="description_fr" class="form-control"
                            value="{{ old('description_fr') }}" rows="8">{{$data->description_fr}}</textarea>
                        @error('description_fr')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('messages.tax') }} %</label>
                        <input name="tax" id="tax" class="form-control" value="{{ old('tax', $data->tax) }}">
                        @error('tax')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('messages.selling_price_for_user') }}</label>
                        <input name="selling_price_for_user" id="selling_price_for_user" class="form-control"
                            value="{{ old('selling_price_for_user', $data->selling_price_for_user) }}">
                        @error('selling_price_for_user')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('messages.min_order_for_user') }}</label>
                        <input name="min_order_for_user" id="min_order_for_user" class="form-control"
                            value="{{ old('min_order_for_user', $data->min_order_for_user) }}">
                        @error('min_order_for_user')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('messages.min_order_for_wholesale') }}</label>
                        <input name="min_order_for_wholesale" id="min_order_for_wholesale" class="form-control"
                            value="{{ old('min_order_for_wholesale', $data->min_order_for_wholesale) }}">
                        @error('min_order_for_wholesale')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> {{ __('messages.Status') }}</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Select</option>
                            <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Active</option>
                            <option value="2" {{ $data->status == 2 ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> {{ __('messages.in_stock') }}</label>
                        <select name="in_stock" id="in_stock" class="form-control">
                            <option value="">Select</option>
                            <option value="1" {{ $data->in_stock == 1 ? 'selected' : '' }}>Yes</option>
                            <option value="2" {{ $data->in_stock == 2 ? 'selected' : '' }}>No</option>
                        </select>
                        @error('in_stock')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>



                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('messages.has_variation') }}</label>
                        <select name="has_variation" id="has_variation" class="form-control">
                            <option value="1" {{ $data->has_variation == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $data->has_variation == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('has_variation')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div id="variationFields">
                    @if ($data->has_variation)
                    @foreach ($data->variations as $variation)
                    <div class="variation">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="attributes[]" placeholder="Attributes"
                                    value="{{ $variation->attributes }}">
                                <br>
                                <br>
                                <input type="text" name="variations[]" placeholder="Variations"
                                    value="{{ $variation->variation }}">
                                <br>
                                <br>
                                <input type="number" name="available_quantities[]" placeholder="Available Quantity"
                                    value="{{ $variation->available_quantity }}">
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>

                <div class="col-md-6">
                    <button type="button" id="add-variation">Add Variation</button>
                </div>

                <div class="col-md-6">
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

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Product Images</label>
                        <input type="file" name="photo[]" class="form-control" multiple>
                    </div>
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
            @foreach ($data->units as $productUnit)
                <div class="row product-unit">
                    <!-- Unit Selection -->
                    <div class="form-group col-md-3">
                        <label for="unit">{{ __('messages.unit_for_wholeSale') }}</label>
                        <select name="units[]" class="form-control" required>
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

            <div class="col-md-12 text-center">
                <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm">Update</button>
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-danger">Cancel</a>
            </div>
        </div>
    </form>
</div>
</div>

</div>






@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#productTab a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        $('#add-unit').on('click', function() {
            const unitTemplate = `
                <div class="row product-unit">
                    <div class="col-md-3">
                        <div class="form-group mt-3">
                            <label for="unit">{{ __('messages.unit') }}</label>
                            <select name="units[]" class="form-control" required>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mt-3">
                            <label for="barcode">{{ __('messages.barcode') }}</label>
                            <input type="number" class="form-control" name="barcodes[]">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mt-3">
                            <label for="releation">{{ __('messages.releation') }}</label>
                            <input type="number" class="form-control" name="releations[]">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="selling_price">{{ __('messages.selling_price') }}</label>
                            <input type="number" class="form-control" name="selling_prices[]" step="any">
                        </div>
                    </div>
                </div>`;
            $('#product-units-container').append(unitTemplate);
        });
    });
</script>
@endsection
