@extends('layouts.admin')
@section('title')
{{ __('messages.deliveries') }}
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> {{ __('messages.deliveries') }} </h3>
        <input type="hidden" id="token_search" value="{{csrf_token() }}">
        <a href="{{ route('deliveries.create') }}" class="btn btn-sm btn-success"> {{ __('messages.New') }} {{ __('messages.deliveries') }}</a>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                {{-- Search functionality can be added here if needed --}}
            </div>
        </div>
        <div class="clearfix"></div>

        <div id="ajax_responce_serarchDiv" class="col-md-12">
            @can('order-table')
            @if (isset($data) && !$data->isEmpty())
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">
                    <th>{{ __('messages.Place') }}</th>
                    <th>{{ __('messages.Price') }}</th>
                    <th>Availabilities</th>
                    <th>{{ __('messages.Action') }}</th>
                </thead>
                <tbody>
                    @foreach ($data as $info)
                    <tr>
                        <td>{{ $info->place }}</td>
                        <td>${{ number_format($info->price, 2) }}</td>
                        <td>
                            @if($info->availabilities && $info->availabilities->count() > 0)
                                @php
                                    $availabilitiesByDay = $info->availabilities->groupBy('day_of_week');
                                @endphp
                                <div class="availability-summary">
                                    @foreach($availabilitiesByDay as $day => $dayAvailabilities)
                                        <div class="day-availability mb-1">
                                            <strong>{{ ucfirst($day) }}:</strong>
                                            @foreach($dayAvailabilities as $availability)
                                                <span class="badge badge-{{ $availability->is_active ? 'success' : 'secondary' }} mr-1">
                                                    {{ $availability->time_from->format('H:i') }} - {{ $availability->time_to->format('H:i') }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted">No availabilities set</span>
                            @endif
                        </td>
                        <td>
                            @can('delivery-edit')
                            <a href="{{ route('deliveries.edit', $info->id) }}" class="btn btn-sm btn-primary mb-1">{{ __('messages.Edit') }}</a>
                            <a href="{{ route('deliveries.availabilities', $info->id) }}" class="btn btn-sm btn-info mb-1">
                                <i class="fas fa-clock"></i> Manage Schedule
                            </a>
                            @endcan
                            
                            @can('delivery-delete')
                            <form action="{{ route('deliveries.destroy', $info->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Are you sure you want to delete this delivery?')">
                                    {{ __('messages.Delete') }}
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
            {{ $data->links() }}

            @else
            <div class="alert alert-danger">
                {{ __('messages.No_data') }}
            </div>
            @endif
            @endcan
        </div>
    </div>
</div>

<style>
.availability-summary {
    max-width: 300px;
}
.day-availability {
    font-size: 0.875rem;
    line-height: 1.4;
}
.badge {
    font-size: 0.75rem;
}
</style>
@endsection

@section('script')
<script src="{{ asset('assets/admin/js/deliveriess.js') }}"></script>
@endsection