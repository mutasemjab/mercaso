@extends('layouts.admin')

@section('title')
{{ __('messages.user_report') }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-3 text-gray-800">{{ __('messages.user_report') }}</h1>
            <form method="GET" action="{{ route('user_report') }}">
                <div class="form-row align-items-end">


                    <div class="form-group col-md-3">
                        <label for="to_date">{{ __('messages.to_date') }}</label>
                        <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date', date('Y-m-d')) }}">
                    </div>
                    <div class="form-group col-md-3">
                        <button type="submit" class="btn btn-primary">{{ __('messages.Show') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(!empty($reportData))
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('messages.user_report') }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.ID') }}</th>
                                    <th>{{ __('messages.Name') }}</th>
                                    <th>{{ __('messages.Email') }}</th>
                                    <th>{{ __('messages.Phone') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reportData as $data)
                                <tr>
                                    <td>{{ $data['id'] }}</td>
                                    <td>{{ $data['name'] }}</td>
                                    <td>{{ $data['email'] }}</td>
                                    <td>{{ $data['phone'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
