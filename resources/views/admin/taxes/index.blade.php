@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Taxes</h3>
                    <a href="{{ route('taxes.create') }}" class="btn btn-primary">Add New Tax</a>
                </div>
                <div class="card-body">
                   
                    @can('tax-table')
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Value (%)</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($taxes as $tax)
                                    <tr>
                                        <td>{{ $tax->id }}</td>
                                        <td>{{ $tax->name }}</td>
                                        <td>{{ $tax->value }}%</td>
                                        <td>{{ $tax->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                              @can('tax-edit')
                                            <a href="{{ route('taxes.edit', $tax) }}" class="btn btn-sm btn-warning">Edit</a>
                                            @endcan  
                                            @can('tax-delete')
                                            <form action="{{ route('taxes.destroy', $tax) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this tax?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No taxes found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection