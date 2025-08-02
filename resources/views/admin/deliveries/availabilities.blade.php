{{-- resources/views/admin/deliveries/availabilities.blade.php --}}
@extends('layouts.admin')
@section('title')
    Manage Availabilities - {{ $delivery->place }}
@endsection

@section('contentheaderlink')
    <a href="{{ route('deliveries.index') }}"> Deliveries </a>
@endsection

@section('contentheaderactive')
    Manage Availabilities
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center">
            Manage Availabilities for: <strong>{{ $delivery->place }}</strong>
        </h3>
        <div class="card-tools">
            <a href="{{ route('deliveries.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Deliveries
            </a>
        </div>
    </div>
    
    <div class="card-body">
        <!-- Delivery Info -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-map-marker-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Delivery Location</span>
                        <span class="info-box-number">{{ $delivery->place }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-dollar-sign"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Delivery Price</span>
                        <span class="info-box-number">${{ number_format($delivery->price, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Availabilities Display -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Current Schedule</h4>
                    </div>
                    <div class="card-body">
                        @if($delivery->availabilities && $delivery->availabilities->count() > 0)
                            @php
                                $daysOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                $availabilitiesByDay = $delivery->availabilities->groupBy('day_of_week');
                                $orderedAvailabilities = collect($daysOrder)->mapWithKeys(function($day) use ($availabilitiesByDay) {
                                    return [$day => $availabilitiesByDay->get($day, collect())];
                                })->filter(function($dayAvailabilities) {
                                    return $dayAvailabilities->isNotEmpty();
                                });
                            @endphp
                            
                            <div class="schedule-display">
                                @foreach($orderedAvailabilities as $day => $dayAvailabilities)
                                    <div class="day-schedule mb-3">
                                        <div class="day-header">
                                            <i class="fas fa-calendar-day text-primary"></i>
                                            <strong>{{ ucfirst($day) }}</strong>
                                        </div>
                                        <div class="time-slots ml-4">
                                            @foreach($dayAvailabilities->sortBy('time_from') as $availability)
                                                <div class="time-slot mb-2">
                                                    <span class="badge badge-{{ $availability->is_active ? 'success' : 'secondary' }} mr-2">
                                                        <i class="fas fa-clock"></i>
                                                        {{ $availability->time_from->format('H:i') }} - {{ $availability->time_to->format('H:i') }}
                                                        @if(!$availability->is_active)
                                                            <small>(Inactive)</small>
                                                        @endif
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                No availability schedules have been set for this delivery location.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Add/Edit Form -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add New Availability</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('deliveries.update', $delivery->id) }}" method="post" id="quick-availability-form">
                            @csrf
                            @method('PUT')
                            
                            <!-- Hidden fields for delivery data -->
                            <input type="hidden" name="place" value="{{ $delivery->place }}">
                            <input type="hidden" name="price" value="{{ $delivery->price }}">
                            
                            <!-- Existing availabilities as hidden fields -->
                            @if($delivery->availabilities)
                                @foreach($delivery->availabilities as $index => $availability)
                                    <input type="hidden" name="availabilities[{{ $index }}][day_of_week]" value="{{ $availability->day_of_week }}">
                                    <input type="hidden" name="availabilities[{{ $index }}][time_from]" value="{{ $availability->time_from->format('H:i') }}">
                                    <input type="hidden" name="availabilities[{{ $index }}][time_to]" value="{{ $availability->time_to->format('H:i') }}">
                                    <input type="hidden" name="availabilities[{{ $index }}][is_active]" value="{{ $availability->is_active ? '1' : '0' }}">
                                @endforeach
                            @endif

                            <!-- New availability form -->
                            <div class="form-group">
                                <label>Day of Week</label>
                                <select name="availabilities[{{ $delivery->availabilities ? $delivery->availabilities->count() : 0 }}][day_of_week]" class="form-control" required>
                                    <option value="">Select Day</option>
                                    @foreach($daysOfWeek as $key => $day)
                                        <option value="{{ $key }}">{{ $day }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Time From</label>
                                        <input type="time" name="availabilities[{{ $delivery->availabilities ? $delivery->availabilities->count() : 0 }}][time_from]" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Time To</label>
                                        <input type="time" name="availabilities[{{ $delivery->availabilities ? $delivery->availabilities->count() : 0 }}][time_to]" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" name="availabilities[{{ $delivery->availabilities ? $delivery->availabilities->count() : 0 }}][is_active]" class="form-check-input" checked>
                                    <label class="form-check-label">Active</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Add Availability
                                </button>
                                <a href="{{ route('deliveries.edit', $delivery->id) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Edit All
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Availability Statistics -->
        @if($delivery->availabilities && $delivery->availabilities->count() > 0)
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Availability Statistics</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>{{ $delivery->availabilities->count() }}</h3>
                                        <p>Total Time Slots</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>{{ $delivery->availabilities->where('is_active', true)->count() }}</h3>
                                        <p>Active Slots</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>{{ $delivery->availabilities->where('is_active', false)->count() }}</h3>
                                        <p>Inactive Slots</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-pause-circle"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="small-box bg-primary">
                                    <div class="inner">
                                        <h3>{{ $delivery->availabilities->groupBy('day_of_week')->count() }}</h3>
                                        <p>Days Covered</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.schedule-display {
    max-height: 400px;
    overflow-y: auto;
}

.day-schedule {
    border-left: 4px solid #007bff;
    padding-left: 15px;
    margin-bottom: 20px;
}

.day-header {
    font-size: 1.1rem;
    margin-bottom: 10px;
    color: #495057;
}

.time-slot {
    margin-left: 20px;
}

.badge {
    font-size: 0.85rem;
    padding: 0.5em 0.75em;
}

.info-box {
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    border-radius: .25rem;
    background: #fff;
    display: flex;
    margin-bottom: 1rem;
    min-height: 80px;
    padding: .5rem;
    position: relative;
    width: 100%;
}

.info-box .info-box-icon {
    border-radius: .25rem;
    align-items: center;
    display: flex;
    font-size: 1.875rem;
    justify-content: center;
    text-align: center;
    width: 70px;
    color: rgba(255,255,255,.8);
    background: rgba(0,0,0,.1);
}

.info-box .info-box-content {
    display: flex;
    flex-direction: column;
    justify-content: center;
    line-height: 1.8;
    margin-left: .5rem;
    padding: 0 .5rem;
}

.small-box {
    border-radius: .25rem;
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    display: block;
    margin-bottom: 20px;
    position: relative;
}

.small-box > .inner {
    padding: 10px;
}

.small-box .icon {
    color: rgba(0,0,0,.15);
    z-index: 0;
}

.small-box .icon > i {
    font-size: 70px;
    position: absolute;
    right: 15px;
    top: 15px;
}

.small-box h3 {
    font-size: 2.2rem;
    font-weight: 700;
    margin: 0 0 10px;
    white-space: nowrap;
    color: #fff;
}

.small-box p {
    font-size: 1rem;
    color: #fff;
    margin: 0;
}

.bg-info { background-color: #17a2b8!important; }
.bg-success { background-color: #28a745!important; }
.bg-warning { background-color: #ffc107!important; color: #1f2d3d!important; }
.bg-primary { background-color: #007bff!important; }
</style>
@endsection

@section('script')
<script>
$(document).ready(function() {
    // Form validation
    $('#quick-availability-form').on('submit', function(e) {
        const timeFrom = $('input[name*="time_from"]').last().val();
        const timeTo = $('input[name*="time_to"]').last().val();
        
        if (timeFrom && timeTo && timeFrom >= timeTo) {
            e.preventDefault();
            alert('End time must be after start time!');
            return false;
        }
    });
});
</script>
@endsection