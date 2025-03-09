@extends('layouts.admin')
@section('title')
    {{ __('messages.Edit') }} {{ __('messages.banners') }}
@endsection



@section('contentheaderlink')
    <a href="{{ route('banners.index') }}"> {{ __('messages.banners') }} </a>
@endsection

@section('contentheaderactive')
    {{ __('messages.Edit') }}
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title text-center">{{ __('messages.Edit') }} {{ __('messages.banners') }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('banners.update', $data['id']) }}" method="post" enctype='multipart/form-data'>
            @csrf
            @method('PUT')
            <div class="row">



                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('messages.with_each_other') }}</label>
                        <select name="with_each_other" id="with_each_other" class="form-control">
                            <option value="">Select</option>
                            <option @if ($data->with_each_other == 1) selected="selected" @endif value="1">Yes</option>
                            <option @if ($data->with_each_other == 2) selected="selected" @endif value="2">No</option>
                        </select>
                        @error('with_each_other')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select class="form-control" name="category" id="category">
                            <option value="">Select Parent Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @if($data->category_id == $cat->id) selected @endif>{{ $cat->name_en }}</option>
                            @endforeach
                            <option value="0" @if($data->category_id === null) selected @endif>No Parent Category</option>
                        </select>
                        @error('category')
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
                        <a href="{{ route('banners.index') }}" class="btn btn-danger btn-sm">{{ __('messages.Cancel') }}</a>
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
