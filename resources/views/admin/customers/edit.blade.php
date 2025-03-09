@extends('layouts.admin')
@section('title')
    {{ __('messages.Edit') }} {{ __('messages.Customers') }}
@endsection



@section('contentheaderlink')
    <a href="{{ route('admin.customer.index') }}"> {{ __('messages.Customers') }} </a>
@endsection

@section('contentheaderactive')
    {{ __('messages.Edit') }}
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.Edit') }} {{ __('messages.Customers') }} </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">




                <form action="{{ route('admin.customer.update', $data['id']) }}" method="POST" enctype='multipart/form-data'>
                    <div class="row">
                    @csrf

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Name') }}</label>
                            <input name="name" id="name" class="form-control"
                                value="{{ old('name', $data['name']) }}">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>




                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.Email') }}</label>
                            <input name="email" id="email" class="form-control" value="{{ old('email', $data['email']) }}">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.Phone') }}</label>
                            <input name="phone" id="phone" class="form-control"
                                value="{{ old('phone', $data['phone']) }}" />
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

                    <div class="form-group col-md-6">
                        <label for="category_id"> {{ __('messages.countries') }}</label>
                        <select class="form-control" name="country" id="category_id">
                            <option value="">Select  countries</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}"
                                    {{ $country->id == $data->country_id ? 'selected' : '' }}>
                                    {{ $country->name_ar }}
                                </option>
                            @endforeach
                        </select>
                        @error('country')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="category_id"> {{ __('messages.representatives') }}</label>
                        <select class="form-control" name="representative" id="category_id">
                            <option value="">Select  representatives</option>
                            @foreach($representatives as $representative)
                                <option value="{{ $representative->id }}"
                                    {{ $representative->id == $data->representative_id ? 'selected' : '' }}>
                                    {{ $representative->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('representative')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>



                    <div class="form-group col-md-6">
                        <label for="photo">Photo</label>
                        <input type="file" name="photo" id="photo" class="form-control-file">
                        @if ($data->photo)
                            <img src="{{ asset('assets/admin/uploads').'/'.$data->photo }}" id="image-preview" alt="Selected Image" height="50px" width="50px">
                        @else
                            <img src="" id="image-preview" alt="Selected Image" height="50px" width="50px" style="display: none;">
                        @endif
                        @error('photo')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.can_pay_with_receivable') }}</label>
                            <select name="can_pay_with_receivable" id="can_pay_with_receivable" class="form-control">
                                <option value="">Select</option>
                                <option @if ($data->can_pay_with_receivable == 1) selected="selected" @endif value="1">Yes</option>
                                <option @if ($data->can_pay_with_receivable == 2) selected="selected" @endif value="2">No</option>
                            </select>
                            @error('can_pay_with_receivable')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Activate') }}</label>
                            <select name="activate" id="activate" class="form-control">
                                <option value="">Select</option>
                                <option @if ($data->activate == 1) selected="selected" @endif value="1">activate</option>
                                <option @if ($data->activate == 2) selected="selected" @endif value="2">Inactivate</option>
                            </select>
                            @error('activate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm"> {{ __('messages.Update') }}</button>
                            <a href="{{ route('admin.customer.index') }}" class="btn btn-sm btn-danger">{{ __('messages.Cancel') }}</a>

                        </div>
                    </div>


            </div>

            </form>

        </div>




    </div>
    </div>
@endsection


@section('script')
    <script src="{{ asset('assets/admin/js/customers.js') }}"></script>
@endsection
