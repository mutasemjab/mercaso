@extends('layouts.admin')
@section('title')
    Wholesale Requests
@endsection

@section('contentheaderlink')
    <a href="{{ route('admin.wholeSale.index') }}"> {{ __('messages.wholeSales') }} </a>
@endsection

@section('contentheaderactive')
    Pending Requests
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center">Wholesale Pending Requests</h3>
        </div>
        <div class="card-body">
            @if (isset($data) && count($data) > 0)
                <table class="table table-bordered table-hover">
                    <thead class="custom_thead">
                        <th>#</th>
                        <th>{{ __('messages.Name') }}</th>
                        <th>{{ __('messages.Email') }}</th>
                        <th>{{ __('messages.Phone') }}</th>
                        <th>Commercial Registration</th>
                        <th>Request Date</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                        @foreach ($data as $info)
                            <tr>
                                <td>{{ $info->id }}</td>
                                <td>{{ $info->user->name ?? 'N/A' }}</td>
                                <td>{{ $info->user->email ?? 'N/A' }}</td>
                                <td>{{ $info->user->phone ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ asset($info->commercial_registration) }}" target="_blank">
                                        <img src="{{ asset($info->commercial_registration) }}" alt="Commercial Registration" width="80" height="80" style="object-fit: cover; border-radius: 4px;">
                                    </a>
                                </td>
                                <td>{{ $info->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <form action="{{ route('admin.wholesaleRequest.approve', $info->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to approve this request?')">
                                            Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.wholesaleRequest.reject', $info->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to reject this request?')">
                                            Reject
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
                {{ $data->links() }}
            @else
                <div class="alert alert-info">
                    No pending wholesale requests.
                </div>
            @endif
        </div>
    </div>
@endsection
