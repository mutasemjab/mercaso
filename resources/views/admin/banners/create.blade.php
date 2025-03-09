@extends('layouts.admin')
@section('title')
    {{ __('messages.banners') }}
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.Add_New') }} {{ __('messages.banners') }} </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


            <form action="{{ route('banners.store') }}" method="post" enctype='multipart/form-data'>
                <div class="row">
                    @csrf



                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.with_each_other') }}</label>
                            <select name="with_each_other" id="with_each_other" class="form-control">
                                <option value="">Select</option>
                                <option value="1">Yes</option>
                                <option value="2">No</option>
                            </select>
                            @error('with_each_other')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="category_id">Category</label>
                        <select class="form-control" name="category" id="category_id">
                            <option value="">Select Parent Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name_en}}</option>
                            @endforeach
                        </select>
                        @error('category')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>





                    <div class="col-md-12">
                        <div class="form-group">
                            <img src="" id="image-preview" alt="Selected Image" height="50px" width="50px"
                                style="display: none;">
                            <button class="btn"> photo</button>
                            <input type="file" id="Item_img" name="photo" class="form-control"
                                onchange="previewImage()">
                            @error('photo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>




                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm"> {{__('messages.Submit')}}</button>
                            <a href="{{ route('banners.index') }}" class="btn btn-sm btn-danger">{{__('messages.Cancel')}}</a>

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
