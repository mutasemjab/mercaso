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

                    <!-- Country Selection -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="country">{{ __('messages.countries') }}</label>
                            <select class="form-control" name="country" id="country">
                                <option value="">Select countries</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ $country->id == $data->country_id ? 'selected' : '' }}>
                                        {{ $country->name_ar }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Representative Selection -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="representative">{{ __('messages.representatives') }}</label>
                            <select class="form-control" name="representative" id="representative">
                                <option value="">Select representatives</option>
                                @foreach($representatives as $representative)
                                    <option value="{{ $representative->id }}" {{ $representative->id == $data->representative_id ? 'selected' : '' }}>
                                        {{ $representative->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('representative')
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

                    <!-- Wholesale Information -->
                    @if ($data->wholeSales)
                        <div class="col-md-6">
                            <div class="image">
                                @if ($data->wholeSales->first()->store_license ?? null)
                                    <img class="custom_img" src="{{ asset('assets/admin/uploads/' . $data->wholeSales->first()->store_license) }}">
                                @else
                                    <p>No store license available.</p>
                                @endif
                            </div>
                            <div class="image">
                                @if ($data->wholeSales->first()->commercial_record ?? null)
                                    <img class="custom_img" src="{{ asset('assets/admin/uploads/' . $data->wholeSales->first()->commercial_record) }}">
                                @else
                                    <p>No commercial record available.</p>
                                @endif
                            </div>
                            <div class="image">
                                @if ($data->wholeSales->first()->import_license ?? null)
                                    <img class="custom_img" src="{{ asset('assets/admin/uploads/' . $data->wholeSales->first()->import_license) }}">
                                @else
                                    <p>No import license available.</p>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="company_type">Company Type</label>
                                <p>{{ $data->wholeSales->first()->company_type ?? 'N/A' }}</p>

                                <label for="other_company_type">Other Company Type</label>
                                <p>{{ $data->wholeSales->first()->other_company_type ?? 'N/A' }}</p>

                                <label for="tax_number">Tax Number</label>
                                <p>{{ $data->wholeSales->first()->tax_number ?? 'N/A' }}</p>
                            </div>
                        </div>
                    @else
                        <div class="col-md-6">
                            <p>No wholesale information available.</p>
                        </div>
                    @endif

                    <!-- Address -->
                    <div class="col-md-6">
                        <label for="location">Location:</label>
                        <p>{{ $data->addresses->first()->address ?? 'N/A' }}</p>
                    </div>

                    <!-- Payment Option -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.can_pay_with_receivable') }}</label>
                            <select name="can_pay_with_receivable" id="can_pay_with_receivable" class="form-control">
                                <option value="">Select</option>
                                <option value="1" {{ $data->can_pay_with_receivable == 1 ? 'selected' : '' }}>Yes</option>
                                <option value="2" {{ $data->can_pay_with_receivable == 2 ? 'selected' : '' }}>No</option>
                            </select>
                            @error('can_pay_with_receivable')
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
