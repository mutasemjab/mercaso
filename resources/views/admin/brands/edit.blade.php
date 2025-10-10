@extends('layouts.admin')
@section('title')
    {{ __('messages.brands') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center">
                {{ __('messages.Edit') }} {{ __('messages.brands') }}
            </h3>
        </div>

        <div class="card-body">
            <form action="{{ route('brands.update', $brand->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Name') }}</label>
                            <input name="name" id="name" class="form-control" 
                                   value="{{ old('name', $brand->name) }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>{{ __('messages.Photo') }}</label><br>
                            @if ($brand->photo)
                                <img src="{{ asset('assets/admin/uploads/' . $brand->photo) }}" 
                                     id="image-preview" alt="Current Image" height="50px" width="50px" 
                                     style="display: block;">
                            @else
                                <img src="" id="image-preview" alt="Selected Image" height="50px" width="50px"
                                     style="display: none;">
                            @endif
                            <input type="file" id="Item_img" name="photo" class="form-control mt-2"
                                   onchange="previewImage()">
                            @error('photo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary btn-sm">
                            {{ __('messages.Update') }}
                        </button>
                        <a href="{{ route('brands.index') }}" class="btn btn-sm btn-danger">
                            {{ __('messages.Cancel') }}
                        </a>
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
