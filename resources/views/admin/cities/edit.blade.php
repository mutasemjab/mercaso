@extends('layouts.admin')
@section('title')
    {{ __('messages.Edit') }} {{ __('messages.cities') }}
@endsection



@section('contentheaderlink')
    <a href="{{ route('cities.index') }}"> {{ __('messages.cities') }} </a>
@endsection

@section('contentheaderactive')
    {{ __('messages.Edit') }}
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.Edit') }} {{ __('messages.cities') }} </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


            <form action="{{ route('cities.update', $data['id']) }}" method="post" enctype='multipart/form-data'>
                <div class="row">
                    @csrf

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Name_en') }}</label>
                            <input name="name_en" id="name_en" class=""
                                value="{{ old('name_en', $data['name_en']) }}">
                            @error('name_en')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Name_ar') }}</label>
                            <input name="name_ar" id="name_ar" class=""
                                value="{{ old('name_ar', $data['name_ar']) }}">
                            @error('name_ar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Name_fr') }}</label>
                            <input name="name_fr" id="name_fr" class=""
                                value="{{ old('name_fr', $data['name_fr']) }}">
                            @error('name_fr')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="country_id">Country</label>
                            <select class="form-control" name="country" id="country_id">
                                <option value="">Select country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name_ar }}</option>
                                @endforeach
                            </select>
                            @error('country')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>



                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm">
                                {{ __('messages.Update') }}</button>
                            <a href="{{ route('cities.index') }}"
                                class="btn btn-sm btn-danger">{{ __('messages.Cancel') }}</a>

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
