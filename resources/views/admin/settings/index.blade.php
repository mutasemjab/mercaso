@extends('layouts.admin')
@section('title')
Setting
@endsection

@section('contentheaderactive')
show
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> Setting </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 table-responsive">

                @if (count($data)>0)
                <div></div>
                @else
                <a href="{{ route('admin.setting.create') }}" class="btn btn-sm btn-success"> New Setting</a>
                @endif
                @can('setting-table')
                @if (@isset($data) && !@empty($data) && count($data)>0)

                <table style="width:100%" id="" class="table">
                    <thead class="custom_thead">
                        <td> Minimum Order For User</td>
                        <td>  Minimum Order For WholeSale</td>
                        <td>Status</td>
                        <td>Action</td>
                    </thead>
                    <tbody>
                        @foreach ($data as $info )
                        <tr>
                            <td>{{ $info->min_order }}</td>
                            <td>{{ $info->min_order_wholeSale }}</td>
                            <td>
                                @can('setting-edit')
                                    <label class="switch">
                                        <input type="checkbox" 
                                               class="status-toggle" 
                                               data-id="{{ $info->id }}" 
                                               {{ $info->status == 1 ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                @else
                                    <span class="badge badge-{{ $info->status == 1 ? 'success' : 'danger' }}">
                                        {{ $info->status == 1 ? 'Active' : 'Inactive' }}
                                    </span>
                                @endcan
                            </td>
                            <td>
                                @can('setting-edit')
                                <a href="{{ route('admin.setting.edit',$info->id) }}"
                                    class="btn btn-sm  btn-primary">edit</a>
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
                    there is no data found !! </div>
                @endif

            </div>
            @endcan

        </div>

    </div>

</div>

@endsection

@section('script')
<style>
/* Toggle Switch Styles */
.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
}

.slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
}

input:checked + .slider {
    background-color: #28a745;
}

input:focus + .slider {
    box-shadow: 0 0 1px #28a745;
}

input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
    border-radius: 34px;
}

.slider.round:before {
    border-radius: 50%;
}

/* Alert styles for notifications */
.alert-fixed {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
}
</style>

<script>
$(document).ready(function() {
    // Function to show notifications
    function showNotification(message, type = 'success') {
        var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        var alertHtml = '<div class="alert ' + alertClass + ' alert-fixed alert-dismissible fade show" role="alert">' +
                       message +
                       '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                       '<span aria-hidden="true">&times;</span>' +
                       '</button>' +
                       '</div>';
        
        $('body').append(alertHtml);
        
        // Auto dismiss after 3 seconds
        setTimeout(function() {
            $('.alert-fixed').fadeOut();
        }, 3000);
    }

    $('.status-toggle').change(function() {
        var settingId = $(this).data('id');
        var isChecked = $(this).is(':checked');
        var toggle = $(this);
        
        // Disable the toggle while processing
        toggle.prop('disabled', true);
        
        // Get the correct URL based on your route structure
        var url = '{{ route("admin.setting.toggleStatus", ":id") }}';
        url = url.replace(':id', settingId);
        
        $.ajax({
            url: url,
            type: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Show success message
                    showNotification(response.message, 'success');
                } else {
                    // Revert the toggle if there was an error
                    toggle.prop('checked', !isChecked);
                    showNotification(response.message, 'error');
                }
            },
            error: function(xhr) {
                console.log('Error:', xhr);
                // Revert the toggle if there was an error
                toggle.prop('checked', !isChecked);
                showNotification('An error occurred while updating the status', 'error');
            },
            complete: function() {
                // Re-enable the toggle
                toggle.prop('disabled', false);
            }
        });
    });
});
</script>

<script src="{{ asset('assets/admin/js/Settings.js') }}"></script>

@endsection