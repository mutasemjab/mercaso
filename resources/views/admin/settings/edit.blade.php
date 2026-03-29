@extends('layouts.admin')
@section('title')

edit Setting
@endsection



@section('contentheaderlink')
<a href="{{ route('admin.setting.index') }}"> Setting </a>
@endsection

@section('contentheaderactive')
تعديل
@endsection


@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> edit Setting </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">


        <form action="{{ route('admin.setting.update',$data['id']) }}" method="post" enctype='multipart/form-data'>
            <div class="row">
                @csrf





                <div class="col-md-6">
                    <div class="form-group">
                        <label> Minimum Order For Normal User </label>
                        <input name="min_order" id="min_order" class="form-control"
                            value="{{ old('min_order',$data['min_order']) }}">
                        @error('min_order')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>  Minimum Order For WholeSale </label>
                        <input name="min_order_wholeSale" id="min_order_wholeSale" class="form-control"
                            value="{{ old('min_order_wholeSale',$data['min_order_wholeSale']) }}">
                        @error('min_order_wholeSale')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> Phone Number </label>
                        <input name="phone_number" id="phone_number" class="form-control"
                            value="{{ old('phone_number',$data['phone_number']) }}">
                        @error('phone_number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Company Information Section -->
                <div class="col-md-12">
                    <h5 class="mt-3 mb-3"><strong>Company Information</strong></h5>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> Company Name </label>
                        <input name="company_name" id="company_name" class="form-control"
                            value="{{ old('company_name',$data['company_name']) }}">
                        @error('company_name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> Street Address </label>
                        <input name="street_address" id="street_address" class="form-control"
                            value="{{ old('street_address',$data['street_address']) }}">
                        @error('street_address')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label> City </label>
                        <input name="city" id="city" class="form-control"
                            value="{{ old('city',$data['city']) }}">
                        @error('city')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label> State </label>
                        <input name="state" id="state" class="form-control"
                            value="{{ old('state',$data['state']) }}">
                        @error('state')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label> Country </label>
                        <input name="country" id="country" class="form-control"
                            value="{{ old('country',$data['country']) }}">
                        @error('country')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label> ZIP Code </label>
                        <input name="zip_code" id="zip_code" class="form-control"
                            value="{{ old('zip_code',$data['zip_code']) }}">
                        @error('zip_code')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> Company Phone </label>
                        <input name="company_phone" id="company_phone" class="form-control"
                            value="{{ old('company_phone',$data['company_phone']) }}">
                        @error('company_phone')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group text-center">
                        <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm"> update</button>
                        <a href="{{ route('admin.setting.index') }}" class="btn btn-sm btn-danger">cancel</a>

                    </div>
                </div>

            </div>
        </form>



    </div>




</div>
</div>






@endsection
