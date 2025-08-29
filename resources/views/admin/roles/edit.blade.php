@extends('layouts.admin')

@section('title', __('messages.edit') . ' ' . __('messages.role'))

@section('css')
<style>
    .permission-group {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        margin-bottom: 1rem;
        background: #f8f9fa;
    }
    
    .permission-group-header {
        background: #e9ecef;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #dee2e6;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .permission-items {
        padding: 1rem;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 0.5rem;
    }
    
    .permission-item {
        display: flex;
        align-items: center;
        padding: 0.25rem 0;
    }
    
    .permission-item input[type="checkbox"] {
        margin-right: 0.5rem;
        transform: scale(1.1);
    }
    
    .module-checkbox {
        margin-left: auto;
    }
    
    .action-table { color: #28a745; }
    .action-add { color: #007bff; }
    .action-edit { color: #ffc107; }
    .action-delete { color: #dc3545; }
    .action-report { color: #17a2b8; }
</style>
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('messages.edit') }} {{ __('messages.role') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.role.index') }}">{{ __('messages.roles') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('messages.edit') }}</li>
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
                            <h3 class="card-title">{{ __('messages.edit') }} {{ __('messages.role') }}: {{ $role->name }}</h3>
                        </div>
                        
                        <form action="{{ route('admin.role.update', $role->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <!-- Role Name -->
                                <div class="form-group">
                                    <label for="name">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $role->name) }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Permissions Section -->
                                <div class="form-group">
                                    <label>{{ __('messages.permissions') }} <span class="text-danger">*</span></label>
                                    
                                    <!-- Select All Options -->
                                    <div class="mb-3">
                                        <button type="button" class="btn btn-sm btn-success" id="selectAll">
                                            {{ __('messages.select_all') }}
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" id="deselectAll">
                                            {{ __('messages.deselect_all') }}
                                        </button>
                                        <span class="badge badge-info ml-2">
                                            {{ count($rolePermissions) }} {{ __('messages.permissions') }} {{ __('messages.selected') }}
                                        </span>
                                    </div>

                                    @error('perms')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror

                                    <!-- Grouped Permissions -->
                                    @foreach($permissions as $module => $modulePermissions)
                                        <div class="permission-group">
                                            <div class="permission-group-header">
                                                <span>
                                                    <i class="fas fa-cog"></i>
                                                    {{ __('permissions.modules.' . $module) }}
                                                </span>
                                                <div class="module-checkbox">
                                                    <input type="checkbox" class="module-select" data-module="{{ $module }}">
                                                    <small class="text-muted">{{ __('messages.select_all') }}</small>
                                                </div>
                                            </div>
                                            <div class="permission-items">
                                                @foreach($modulePermissions as $permission)
                                                    <div class="permission-item">
                                                        <input type="checkbox" 
                                                               class="permission-checkbox" 
                                                               data-module="{{ $module }}"
                                                               name="perms[]" 
                                                               value="{{ $permission['id'] }}" 
                                                               id="perm_{{ $permission['id'] }}"
                                                               {{ in_array($permission['id'], old('perms', $rolePermissions)) ? 'checked' : '' }}>
                                                        <label for="perm_{{ $permission['id'] }}" class="mb-0">
                                                            <span class="action-{{ $permission['action'] }}">
                                                                @switch($permission['action'])
                                                                    @case('table')
                                                                        <i class="fas fa-table"></i>
                                                                        @break
                                                                    @case('add')
                                                                        <i class="fas fa-plus"></i>
                                                                        @break
                                                                    @case('edit')
                                                                        <i class="fas fa-edit"></i>
                                                                        @break
                                                                    @case('delete')
                                                                        <i class="fas fa-trash"></i>
                                                                        @break
                                                                    @case('report')
                                                                        <i class="fas fa-chart-bar"></i>
                                                                        @break
                                                                    @default
                                                                        <i class="fas fa-cog"></i>
                                                                @endswitch
                                                            </span>
                                                            {{ $permission['display_name'] }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> {{ __('messages.save') }}
                                </button>
                                <a href="{{ route('admin.role.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@push('scripts')
<script>
$(document).ready(function() {
    // Select All functionality
    $('#selectAll').click(function() {
        $('.permission-checkbox').prop('checked', true);
        $('.module-select').prop('checked', true);
        updateSelectedCount();
    });
    
    // Deselect All functionality
    $('#deselectAll').click(function() {
        $('.permission-checkbox').prop('checked', false);
        $('.module-select').prop('checked', false);
        updateSelectedCount();
    });
    
    // Module select functionality
    $('.module-select').change(function() {
        const module = $(this).data('module');
        const isChecked = $(this).is(':checked');
        $(`.permission-checkbox[data-module="${module}"]`).prop('checked', isChecked);
        updateSelectedCount();
    });
    
    // Individual permission change
    $('.permission-checkbox').change(function() {
        const module = $(this).data('module');
        const totalInModule = $(`.permission-checkbox[data-module="${module}"]`).length;
        const checkedInModule = $(`.permission-checkbox[data-module="${module}"]:checked`).length;
        
        $(`.module-select[data-module="${module}"]`).prop('checked', totalInModule === checkedInModule);
        updateSelectedCount();
    });
    
    // Initialize module checkboxes
    $('.module-select').each(function() {
        const module = $(this).data('module');
        const totalInModule = $(`.permission-checkbox[data-module="${module}"]`).length;
        const checkedInModule = $(`.permission-checkbox[data-module="${module}"]:checked`).length;
        
        $(this).prop('checked', totalInModule === checkedInModule);
    });
    
    // Update selected count
    function updateSelectedCount() {
        const selectedCount = $('.permission-checkbox:checked').length;
        $('.badge-info').text(selectedCount + ' {{ __("messages.permissions") }} {{ __("messages.selected") }}');
    }
    
    // Initial count
    updateSelectedCount();
});
</script>
@endpush
@endsection