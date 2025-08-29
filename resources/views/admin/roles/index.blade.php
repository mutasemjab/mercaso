@extends('layouts.admin')

@section('title', __('messages.role_management'))

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.role_management') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.roles') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="card-title">{{ __('messages.roles') }}</h3>
                                </div>
                                <div class="col-md-6 text-right">
                                    @can('role-add')
                                        <a href="{{ route('admin.role.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> {{ __('messages.create') }}
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Search Form -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <form method="GET" action="{{ route('admin.role.index') }}">
                                        <div class="input-group">
                                            <input type="text" name="search" class="form-control" 
                                                   placeholder="{{ __('messages.search') }}..." 
                                                   value="{{ request('search') }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="submit">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Roles Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.no') }}</th>
                                            <th>{{ __('messages.name') }}</th>
                                            <th>{{ __('messages.permissions') }}</th>
                                            <th>{{ __('messages.created_at') }}</th>
                                            <th>{{ __('messages.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data as $key => $role)
                                            <tr>
                                                <td>{{ $data->firstItem() + $key }}</td>
                                                <td>
                                                    <strong>{{ $role->name }}</strong>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info">
                                                        {{ $role->permissions->count() }} {{ __('messages.permissions') }}
                                                    </span>
                                                </td>
                                                <td>{{ $role->created_at->format('Y-m-d H:i') }}</td>
                                                <td>
                                                    @can('role-edit')
                                                        <a href="{{ route('admin.role.edit', $role->id) }}" 
                                                           class="btn btn-sm btn-info">
                                                            <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                                                        </a>
                                                    @endcan
                                                    
                                                    @can('role-delete')
                                                        <button type="button" 
                                                                class="btn btn-sm btn-danger delete-btn" 
                                                                data-id="{{ $role->id }}">
                                                            <i class="fas fa-trash"></i> {{ __('messages.delete') }}
                                                        </button>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">{{ __('messages.no_data_found') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="row">
                                <div class="col-sm-12 col-md-5">
                                    <div class="dataTables_info">
                                        {{ __('messages.showing') }} {{ $data->firstItem() }} {{ __('messages.to') }} {{ $data->lastItem() }} {{ __('messages.of') }} {{ $data->total() }} {{ __('messages.entries') }}
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-7">
                                    {{ $data->appends(request()->query())->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.confirm_delete') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ __('messages.confirm_delete') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    {{ __('messages.cancel') }}
                </button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    {{ __('messages.delete') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        let deleteId = null;
        
        $('.delete-btn').click(function() {
            deleteId = $(this).data('id');
            $('#deleteModal').modal('show');
        });
        
        $('#confirmDelete').click(function() {
            if (deleteId) {
                $.ajax({
                    url: '{{ route("admin.role.delete") }}',
                    type: 'POST',
                    data: {
                        id: deleteId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#deleteModal').modal('hide');
                        location.reload();
                    },
                    error: function() {
                        alert('{{ __("messages.error_occurred") }}');
                        $('#deleteModal').modal('hide');
                    }
                });
            }
        });
    });
</script>
@endpush
@endsection