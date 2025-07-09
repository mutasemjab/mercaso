@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Edit CRV</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('crvs.update', $crv) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">CRV Name</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $crv->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="value" class="form-label">CRV Value</label>
                            <input type="number" 
                                   class="form-control @error('value') is-invalid @enderror" 
                                   id="value" 
                                   name="value" 
                                   step="0.01" 
                                   min="0"
                                   value="{{ old('value', $crv->value) }}" 
                                   required>
                            @error('value')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('crvs.index') }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update CRV</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection