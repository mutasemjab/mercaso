@extends('layouts.admin')
@section('title')
    {{ __('messages.categories') }}
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.Add_New') }} {{ __('messages.categories') }} </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">


            <form action="{{ route('categories.store') }}" method="post" enctype='multipart/form-data'>
                <div class="row">
                    @csrf

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.Name_en') }}</label>
                            <input name="name_en" id="name_en" class="form-control" value="{{ old('name_en') }}">
                            @error('name_en')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.Name_ar') }}</label>
                            <input name="name_ar" id="name_ar" class="form-control" value="{{ old('name_ar') }}">
                            @error('name_ar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>



                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.description_en') }}</label>
                            <textarea name="description_en" id="description_en" class="form-control" value="{{ old('description_en') }}" rows="8"></textarea>
                            @error('description_en')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label> {{ __('messages.description_ar') }}</label>
                            <textarea name="description_ar" id="description_ar" class="form-control" value="{{ old('description_ar') }}" rows="8"></textarea>
                            @error('description_ar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>



                    <div class="form-group col-md-6">
                        <label for="category_id">Parent Category</label>
                        <select class="form-control" name="category_id" id="category_id">
                            <option value="">Select Parent Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name_ar }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.in_home_screen') }}</label>
                            <select name="in_home_screen" id="in_home_screen" class="form-control">
                                <option value="">Select</option>
                                <option selected="selected" value="1">Yes</option>
                                <option  value="2">No</option>
                            </select>
                            @error('in_home_screen')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label>{{ __('messages.photo') }} (Multiple)</label>
                            <div id="image-preview-container" style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 10px;"></div>
                            <input type="file" id="Item_img" name="photos[]" class="form-control"
                                onchange="previewImages()" multiple accept="image/*">
                            @error('photos')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>




                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm"> {{__('messages.Submit')}}</button>
                            <a href="{{ route('categories.index') }}" class="btn btn-sm btn-danger">{{__('messages.Cancel')}}</a>

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
