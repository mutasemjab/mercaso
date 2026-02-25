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
                        <label>{{ __('messages.in_home_screen') }}</label>
                        <select name="in_home_screen" id="in_home_screen" class="form-control">
                            <option value="">Select</option>
                            <option @if ($data->in_home_screen == 1) selected="selected" @endif value="1">Yes</option>
                            <option @if ($data->in_home_screen == 2) selected="selected" @endif value="2">No</option>
                        </select>
                        @error('in_home_screen')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>{{ __('messages.photo') }} (Multiple)</label>

                        <!-- Display Existing Images -->
                        @if($data->photo)
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 8px; font-weight: bold;">{{ __('messages.existing_images') }}:</label>
                                <div id="existing-images" style="display: flex; gap: 10px; flex-wrap: wrap;">
                                    @php
                                        $imagePaths = explode(',', $data->photo);
                                    @endphp
                                    @foreach($imagePaths as $imagePath)
                                        @if(trim($imagePath))
                                            <div style="position: relative;">
                                                <img src="{{ asset(trim($imagePath)) }}" alt="Category Image" style="height: 100px; width: 100px; object-fit: cover; border-radius: 5px; border: 2px solid #ddd;">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- New Images Preview -->
                        <div id="image-preview-container" style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 10px;"></div>

                        <!-- File Input -->
                        <input type="file" id="Item_img" name="photos[]" class="form-control-file" onchange="previewImages()" multiple accept="image/*">
                        @error('photos')
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
        function previewImages() {
            var previewContainer = document.getElementById('image-preview-container');
            var input = document.getElementById('Item_img');
            previewContainer.innerHTML = '';

            if (input.files && input.files.length > 0) {
                for (let i = 0; i < input.files.length; i++) {
                    let file = input.files[i];
                    let reader = new FileReader();

                    reader.onload = function(e) {
                        let img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.height = '100px';
                        img.style.width = '100px';
                        img.style.objectFit = 'cover';
                        img.style.borderRadius = '5px';
                        img.style.border = '2px solid #ddd';
                        previewContainer.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                }
            }
        }
    </script>
@endsection
