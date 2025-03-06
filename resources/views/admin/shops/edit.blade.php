@extends('layouts.admin')
@section('title')
    {{ __('messages.Edit') }} {{ __('messages.shops') }}
@endsection



@section('contentheaderlink')
    <a href="{{ route('shops.index') }}"> {{ __('messages.shops') }} </a>
@endsection

@section('contentheaderactive')
    {{ __('messages.Edit') }}
@endsection


@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title text-center">{{ __('messages.Edit') }} {{ __('messages.shops') }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('shops.update', $data['id']) }}" method="post" enctype='multipart/form-data'>
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">{{ __('messages.Name') }}</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $data['name']) }}">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">{{ __('messages.name_of_manager') }}</label>
                        <input type="text" name="name_of_manager" id="name_of_manager" class="form-control" value="{{ old('name_of_manager', $data['name_of_manager']) }}">
                        @error('name_of_manager')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">{{ __('messages.Email') }}</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $data['email']) }}">
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">{{ __('messages.Password') }}</label>
                        <input type="password" name="password" id="password" class="form-control" value="{{ old('password', $data['password']) }}">
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone">{{ __('messages.Phone') }}</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $data['phone']) }}">
                        @error('phone')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="address">{{ __('messages.address') }}</label>
                        <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $data['address']) }}">
                        @error('address')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="country">{{ __('messages.country') }}</label>
                        <select class="form-control" name="country" id="country_id">
                            <option value="">Select country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" @if($data->country_id == $country->id) selected @endif>{{ $country->name_ar }}</option>
                            @endforeach
                            <option value="0" @if($data->country_id === null) selected @endif>No country</option>
                        </select>
                        @error('country')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="active">{{ __('messages.Activate') }}</label>
                        <select name="active" id="active" class="form-control">
                            <option @if ($data->active == 1) selected="selected" @endif value="1">Active</option>
                            <option @if ($data->active == 2) selected="selected" @endif value="2">Inactive</option>
                        </select>
                        @error('active')
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
                        <a href="{{ route('shops.index') }}" class="btn btn-danger btn-sm">{{ __('messages.Cancel') }}</a>
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
