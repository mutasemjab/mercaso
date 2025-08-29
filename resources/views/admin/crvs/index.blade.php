@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>CRVs</h3>
                    <a href="{{ route('crvs.create') }}" class="btn btn-primary">Add New CRV</a>
                </div>
                <div class="card-body">
               

                    <div class="table-responsive">
                           @can('crv-table')
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Value</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($crvs as $crv)
                                    <tr>
                                        <td>{{ $crv->id }}</td>
                                        <td>{{ $crv->name }}</td>
                                        <td>{{ $crv->value }}</td>
                                        <td>{{ $crv->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            @can('crv-edit')
                                            <a href="{{ route('crvs.edit', $crv) }}" class="btn btn-sm btn-warning">Edit</a>
                                             @endcan  
                                            @can('crv-delete')
                                            <form action="{{ route('crvs.destroy', $crv) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this CRV?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No CRVs found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection