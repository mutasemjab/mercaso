@extends('layouts.admin')

@section('title')
    {{ __('messages.Edit') }} {{ __('messages.wholeSales') }}
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.wholeSale.index') }}"> {{ __('messages.wholeSales') }} </a>
@endsection

@section('contentheaderactive')
    {{ __('messages.Edit') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center">{{ __('messages.Edit') }} {{ __('messages.wholeSales') }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.wholeSale.update', $data['id']) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Name Field -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Name') }}</label>
                            <input name="name" id="name" class="form-control" value="{{ old('name', $data['name']) }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Email') }}</label>
                            <input name="email" id="email" class="form-control" value="{{ old('email', $data['email']) }}">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Phone Field -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Phone') }}</label>
                            <input name="phone" id="phone" class="form-control" value="{{ old('phone', $data['phone']) }}">
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Password') }}</label>
                            <input name="password" id="password" class="form-control" value="">
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>



                    <!-- Photo Upload -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="photo">Photo</label>
                            <input type="file" name="photo" id="photo" class="form-control-file">
                            @if ($data->photo)
                                <img src="{{ asset('assets/admin/uploads/' . $data->photo) }}" id="image-preview" alt="Selected Image" height="50px" width="50px">
                            @else
                                <img src="" id="image-preview" alt="Selected Image" height="50px" width="50px" style="display: none;">
                            @endif
                            @error('photo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <!-- Activation Status -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Activate') }}</label>
                            <select name="activate" id="activate" class="form-control">
                                <option value="">Select</option>
                                <option value="1" {{ $data->activate == 1 ? 'selected' : '' }}>Active</option>
                                <option value="2" {{ $data->activate == 2 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('activate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm">{{ __('messages.Update') }}</button>
                            <a href="{{ route('admin.wholeSale.index') }}" class="btn btn-sm btn-danger">{{ __('messages.Cancel') }}</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/admin/js/wholeSales.js') }}"></script>
@endsection
