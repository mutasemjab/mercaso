@extends('layouts.admin')
@section('title')
{{ __('messages.deliveries') }}
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> {{ __('messages.New') }} {{ __('messages.deliveries') }} </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <form action="{{ route('deliveries.store') }}" method="post" enctype='multipart/form-data'>
            <div class="row">
                @csrf

                <div class="col-md-6">
                    <div class="form-group">
                        <label> {{ __('messages.Place') }} </label>
                        <input name="place" id="place" class="form-control" value="{{ old('place') }}" required>
                        @error('place')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> {{ __('messages.Price') }} </label>
                        <input name="price" id="price" type="number" step="0.01" class="form-control" value="{{ old('price') }}" required>
                        @error('price')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Availability Section -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Delivery Availabilities</h4>
                            <button type="button" class="btn btn-sm btn-success float-right" id="add-availability">
                                Add Availability
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="availabilities-container">
                                <!-- Availability rows will be added here -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group text-center">
                        <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm">{{ __('messages.Submit') }}</button>
                        <a href="{{ route('deliveries.index') }}" class="btn btn-sm btn-danger">{{ __('messages.Cancel') }}</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Availability Row Template -->
<div id="availability-template" style="display: none;">
    <div class="availability-row card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Day of Week</label>
                        <select name="availabilities[INDEX][day_of_week]" class="form-control" required>
                            <option value="">Select Day</option>
                            @foreach($daysOfWeek as $key => $day)
                                <option value="{{ $key }}">{{ $day }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Time From</label>
                        <input type="time" name="availabilities[INDEX][time_from]" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Time To</label>
                        <input type="time" name="availabilities[INDEX][time_to]" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Active</label>
                        <div class="form-check">
                            <input type="checkbox" name="availabilities[INDEX][is_active]" class="form-check-input" checked>
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-danger btn-sm form-control remove-availability">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    let availabilityIndex = 0;

    // Add availability row
    $('#add-availability').click(function() {
        let template = $('#availability-template').html();
        template = template.replace(/INDEX/g, availabilityIndex);
        $('#availabilities-container').append(template);
        availabilityIndex++;
    });

    // Remove availability row
    $(document).on('click', '.remove-availability', function() {
        $(this).closest('.availability-row').remove();
    });

    // Add first availability row on page load
    $('#add-availability').click();
});
</script>
@endsection