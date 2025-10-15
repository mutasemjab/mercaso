@extends('layouts.admin')
@section('title')
    Edit Delivery
@endsection

@section('contentheaderlink')
    <a href="{{ route('deliveries.index') }}"> Delivery </a>
@endsection

@section('contentheaderactive')
    Edit
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> Edit Delivery</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <form action="{{ route('deliveries.update', $data['id']) }}" method="post" enctype='multipart/form-data'>
                <div class="row">
                    @csrf
                    @method('PUT')

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Place Name</label>
                            <input name="place" id="place" class="form-control" value="{{ old('place', $data['place']) }}" required>
                            @error('place')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Price</label>
                            <input name="price" id="price" type="number" step="any" class="form-control" value="{{ old('price', $data['price']) }}" required>
                            @error('price')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                      <div class="col-md-4">
                    <div class="form-group">
                        <label> Zip Code</label>
                        <input name="zip_code" id="zip_code" type="number" step="any" class="form-control" value="{{ old('zip_code', $data['zip_code']) }}">
                        @error('zip_code')
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
                                    @if($data->availabilities && $data->availabilities->count() > 0)
                                        @foreach($data->availabilities as $index => $availability)
                                            <div class="availability-row card mb-3">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Day of Week</label>
                                                                <select name="availabilities[{{ $index }}][day_of_week]" class="form-control" required>
                                                                    <option value="">Select Day</option>
                                                                    @foreach($daysOfWeek as $key => $day)
                                                                        <option value="{{ $key }}" {{ old("availabilities.{$index}.day_of_week", $availability->day_of_week) == $key ? 'selected' : '' }}>
                                                                            {{ $day }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Time From</label>
                                                                <input type="time" name="availabilities[{{ $index }}][time_from]" class="form-control" 
                                                                       value="{{ old("availabilities.{$index}.time_from", $availability->time_from ? $availability->time_from->format('H:i') : '') }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Time To</label>
                                                                <input type="time" name="availabilities[{{ $index }}][time_to]" class="form-control" 
                                                                       value="{{ old("availabilities.{$index}.time_to", $availability->time_to ? $availability->time_to->format('H:i') : '') }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label>Active</label>
                                                                <div class="form-check">
                                                                    <input type="checkbox" name="availabilities[{{ $index }}][is_active]" class="form-check-input" 
                                                                           {{ old("availabilities.{$index}.is_active", $availability->is_active) ? 'checked' : '' }}>
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
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm">Update</button>
                            <a href="{{ route('deliveries.index') }}" class="btn btn-sm btn-danger">Cancel</a>
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
    let availabilityIndex = {{ $data->availabilities ? $data->availabilities->count() : 0 }};

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
});
</script>
@endsection