@extends('layouts.admin')
@section('title')
    {{ __('messages.categories') }}
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.Add_New') }} {{ __('messages.categories') }} </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


            <form action="{{ route('categories.store') }}" method="post" enctype='multipart/form-data'>
                <div class="row">
                    @csrf

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.Name_en') }}</label>
                            <input name="name_en" id="name_en" class="form-control" value="{{ old('name_en') }}">
                            @error('name_en')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.Name_ar') }}</label>
                            <input name="name_ar" id="name_ar" class="form-control" value="{{ old('name_ar') }}">
                            @error('name_ar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.name_fr') }}</label>
                            <input name="name_fr" id="name_fr" class="form-control" value="{{ old('name_fr') }}">
                            @error('name_fr')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.description_en') }}</label>
                            <textarea name="description_en" id="description_en" class="form-control" value="{{ old('description_en') }}" rows="8"></textarea>
                            @error('description_en')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.description_ar') }}</label>
                            <textarea name="description_ar" id="description_ar" class="form-control" value="{{ old('description_ar') }}" rows="8"></textarea>
                            @error('description_ar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.description_fr') }}</label>
                            <textarea name="description_fr" id="description_fr" class="form-control" value="{{ old('description_fr') }}" rows="8"></textarea>
                            @error('description_fr')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.color') }}</label>
                            <input type="color" name="color" id="color" class="form-control" value="{{ old('color') }}">
                            @error('color')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.color_picker') }}</label>
                            <input type="color" name="color_picker" id="color_picker" class="form-control" value="{{ old('color_picker') }}">
                            @error('color_picker')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="category_id">Parent Category</label>
                        <select class="form-control" name="category_id" id="category_id">
                            <option value="">Select Parent Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name_ar }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="form-group col-md-6">
                        <label for="countries">Country</label>
                        <select class="form-control" name="country_ids[]" id="countries" multiple>
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name_ar }}</option>
                            @endforeach
                        </select>
                        @error('country_ids')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <img src="" id="image-preview" alt="Selected Image" height="50px" width="50px"
                                style="display: none;">
                            <button class="btn"> photo</button>
                            <input type="file" id="Item_img" name="photo" class="form-control"
                                onchange="previewImage()">
                            @error('photo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>




                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm"> {{__('messages.Submit')}}</button>
                            <a href="{{ route('categories.index') }}" class="btn btn-sm btn-danger">{{__('messages.Cancel')}}</a>

                        </div>
                    </div>

                </div>
            </form>



        </div>




    </div>
    </div>
@endsection


@section('script')
    <script>
        function previewImage() {
            var preview = document.getElementById('image-preview');
            var input = document.getElementById('Item_img');
            var file = input.files[0];
            if (file) {
                preview.style.display = "block";
                var reader = new FileReader();
                reader.onload = function() {
                    preview.src = reader.result;
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = "none";
            }
        }
    </script>
@endsection
