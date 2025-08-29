@extends('layouts.admin')

@section('title', __('messages.create') . ' ' . __('messages.employee'))

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.create') }} {{ __('messages.employee') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.employee.index') }}">{{ __('messages.employees') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.create') }}</li>
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
                            <h3 class="card-title">{{ __('messages.create') }} {{ __('messages.employee') }}</h3>
                        </div>
                        
                        <form action="{{ route('admin.employee.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <!-- Name -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name') }}" required>
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">{{ __('messages.email') }} <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" name="email" value="{{ old('email') }}" required>
                                            @error('email')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Username -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="username">{{ __('messages.username') }}</label>
                                            <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                                   id="username" name="username" value="{{ old('username') }}">
                                            @error('username')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Mobile -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mobile">{{ __('messages.mobile') }}</label>
                                            <input type="text" class="form-control @error('mobile') is-invalid @enderror" 
                                                   id="mobile" name="mobile" value="{{ old('mobile') }}">
                                            @error('mobile')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Password -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">{{ __('messages.password') }} <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                   id="password" name="password" required>
                                            @error('password')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation">{{ __('messages.confirm_password') }} <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" 
                                                   id="password_confirmation" name="password_confirmation" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Roles Section -->
                                <div class="form-group">
                                    <label>{{ __('messages.roles') }} <span class="text-danger">*</span></label>
                                    @error('roles')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                    
                                    <div class="row">
                                        @foreach($roles as $role)
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="roles[]" value="{{ $role->id }}" 
                                                           id="role_{{ $role->id }}"
                                                           {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="role_{{ $role->id }}">
                                                        <strong>{{ $role->name }}</strong>
                                                        <small class="d-block text-muted">
                                                            {{ $role->permissions->count() }} {{ __('messages.permissions') }}
                                                        </small>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> {{ __('messages.save') }}
                                </button>
                                <a href="{{ route('admin.employee.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection