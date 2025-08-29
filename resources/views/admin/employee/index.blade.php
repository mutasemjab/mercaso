@extends('layouts.admin')

@section('title', __('messages.employee_management'))

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.employee_management') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.employees') }}</li>
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
                                    <h3 class="card-title">{{ __('messages.employees') }}</h3>
                                </div>
                                <div class="col-md-6 text-right">
                                    @can('employee-add')
                                        <a href="{{ route('admin.employee.create') }}" class="btn btn-primary">
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
                                    <form method="GET" action="{{ route('admin.employee.index') }}">
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

                            <!-- Employees Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.no') }}</th>
                                            <th>{{ __('messages.name') }}</th>
                                            <th>{{ __('messages.email') }}</th>
                                            <th>{{ __('messages.username') }}</th>
                                            <th>{{ __('messages.mobile') }}</th>
                                            <th>{{ __('messages.roles') }}</th>
                                            <th>{{ __('messages.created_at') }}</th>
                                            <th>{{ __('messages.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data as $key => $employee)
                                            <tr>
                                                <td>{{ $data->firstItem() + $key }}</td>
                                                <td>
                                                    <strong>{{ $employee->name }}</strong>
                                                </td>
                                                <td>{{ $employee->email }}</td>
                                                <td>{{ $employee->username ?? '-' }}</td>
                                                <td>{{ $employee->phone ?? '-' }}</td>
                                                <td>
                                                    @foreach($employee->roles as $role)
                                                        <span class="badge badge-primary">{{ $role->name }}</span>
                                                    @endforeach
                                                </td>
                                                <td>{{ $employee->created_at->format('Y-m-d H:i') }}</td>
                                                <td>
                                                    @can('employee-edit')
                                                        <a href="{{ route('admin.employee.edit', $employee->id) }}" 
                                                           class="btn btn-sm btn-info">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                    
                                                    @can('employee-delete')
                                                        <form action="{{ route('admin.employee.destroy', $employee->id) }}" 
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="btn btn-sm btn-danger"
                                                                    onclick="return confirm('{{ __('messages.confirm_delete') }}')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">{{ __('messages.no_data_found') }}</td>
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
@endsection