@extends('layouts.admin')
@section('title')
    {{ __('messages.Edit') }} {{ __('messages.categories') }}
@endsection



@section('contentheaderlink')
    <a href="{{ route('categories.index') }}"> {{ __('messages.categories') }} </a>
@endsection

@section('contentheaderactive')
    {{ __('messages.Edit') }}
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title text-center">{{ __('messages.Edit') }} {{ __('messages.categories') }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('categories.update', $data['id']) }}" method="post" enctype='multipart/form-data'>
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name_en">{{ __('messages.Name_en') }}</label>
                        <input type="text" name="name_en" id="name_en" class="form-control" value="{{ old('name_en', $data['name_en']) }}">
                        @error('name_en')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name_ar">{{ __('messages.Name_ar') }}</label>
                        <input type="text" name="name_ar" id="name_ar" class="form-control" value="{{ old('name_ar', $data['name_ar']) }}">
                        @error('name_ar')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name_fr">{{ __('messages.Name_fr') }}</label>
                        <input type="text" name="name_fr" id="name_fr" class="form-control" value="{{ old('name_fr', $data['name_fr']) }}">
                        @error('name_fr')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="description_en">{{ __('messages.description_en') }}</label>
                        <textarea name="description_en" id="description_en" class="form-control" rows="8">{{ old('description_en', $data['description_en']) }}</textarea>
                        @error('description_en')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="description_ar">{{ __('messages.description_ar') }}</label>
                        <textarea name="description_ar" id="description_ar" class="form-control" rows="8">{{ old('description_ar', $data['description_ar']) }}</textarea>
                        @error('description_ar')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="description_fr">{{ __('messages.description_fr') }}</label>
                        <textarea name="description_fr" id="description_fr" class="form-control" rows="8">{{ old('description_fr', $data['description_fr']) }}</textarea>
                        @error('description_fr')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="color">{{ __('messages.color') }}</label>
                        <input type="color" name="color" id="color" class="form-control" value="{{ old('color', $data['color']) }}">
                        @error('color')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="color_picker">{{ __('messages.color_picker') }}</label>
                        <input type="color" name="color_picker" id="color_picker" class="form-control" value="{{ old('color_picker', $data['color_picker']) }}">
                        @error('color_picker')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="category_id">Parent Category</label>
                        <select class="form-control" name="category_id" id="category_id">
                            <option value="">Select Parent Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @if($data->category_id == $cat->id) selected @endif>{{ $cat->name_ar }}</option>
                            @endforeach
                            <option value="0" @if($data->category_id === null) selected @endif>No Parent Category</option>
                        </select>
                        @error('category_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="countries">Country</label>
                        <select class="form-control" name="country_ids[]" id="countries" multiple>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" @if(in_array($country->id, $data->countries->pluck('id')->toArray())) selected @endif>{{ $country->name_ar }}</option>
                            @endforeach
                        </select>
                        @error('country_ids')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <img src="" id="image-preview" alt="Selected Image" height="50px" width="50px" style="display: none;">
                        <input type="file" id="Item_img" name="photo" class="form-control-file" onchange="previewImage()">
                        @error('photo')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12 text-center">
                    <div class="form-group">
                        <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm">{{ __('messages.Update') }}</button>
                        <a href="{{ route('categories.index') }}" class="btn btn-danger btn-sm">{{ __('messages.Cancel') }}</a>
                    </div>
                </div>
            </div>
        </form>
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
