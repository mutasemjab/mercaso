@extends('layouts.admin')
@section('title')
    {{ __('messages.products') }}
@endsection

@section('contentheaderactive')
    {{ __('messages.show') }}
@endsection

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.products') }}</h3>

            <a href="{{ route('products.import.show') }}" class="btn btn-sm btn-success">
                {{ __('messages.productsImport') }}
            </a>
            <a href="{{ route('products.create') }}" class="btn btn-sm btn-success"> {{ __('messages.New') }}
                {{ __('messages.products') }}
            </a>

            <!-- Search Form -->
            <form action="{{ route('products.index') }}" method="GET" class="form-inline float-right">
                <input type="text" name="search" class="form-control mr-sm-2" placeholder="{{ __('messages.Search') }}">
                <button type="submit" class="btn btn-primary">{{ __('messages.Search') }}</button>
            </form>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                <div class="col-md-4"></div>
            </div>
            <div class="clearfix"></div>
            <div id="ajax_responce_serarchDiv" class="col-md-12">
                @if (isset($data) && !$data->isEmpty())
                    @can('product-table')
                        <table id="example2" class="table table-bordered table-hover">
                            <thead class="custom_thead">
                                <th>{{ __('messages.Name') }}</th>
                                <th>{{ __('messages.Price') }}</th>
                                <th>{{ __('messages.barcode') }}</th>
                                <th>{{ __('messages.Number') }}</th>
                                <th>{{ __('messages.Categories') }}</th>

                                <th>{{ __('messages.Photo') }}</th>
                                <th>{{ __('messages.Status') }}</th>
                                <th>{{ __('messages.Action') }}</th>
                            </thead>
                            <tbody>
                                @foreach ($data as $info)
                                    <tr>
                                        <td>{{ $info->name_ar }}</td>
                                        <td>{{ $info->selling_price_for_user }}</td>
                                        <td>{{ $info->barcode }}</td>
                                        <td>{{ $info->number }}</td>
                                        <td>
                                            @if ($info->category)
                                                <a
                                                    href="{{ route('categories.index', ['id' => $info->category->id]) }}">{{ $info->category->name_ar }}</a>
                                            @else
                                                No Category
                                            @endif
                                        </td>

                                        <td>
                                            @if ($info->productImages->isNotEmpty())
                                                <div class="image">
                                                    <img class="custom_img"
                                                        src="{{ asset('assets/admin/uploads/' . $info->productImages->first()->photo) }}"
                                                        alt="Product Image">
                                                </div>
                                            @else
                                                No Photo
                                            @endif
                                        </td>
                                        <td>
                                            @can('product-edit')
                                                <label class="switch">
                                                    <input type="checkbox" class="status-toggle" data-id="{{ $info->id }}"
                                                        {{ $info->status == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            @else
                                                <span class="badge badge-{{ $info->status == 1 ? 'success' : 'danger' }}">
                                                    {{ $info->status == 1 ? 'Active' : 'Not Active' }}
                                                </span>
                                            @endcan
                                        </td>
                                        <td>
                                            @can('product-edit')
                                                <a href="{{ route('products.edit', $info->id) }}"
                                                    class="btn btn-sm btn-primary">{{ __('messages.Edit') }}</a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endcan
                    <br>
                    {{ $data->links() }}
                @else
                    <div class="alert alert-danger">{{ __('messages.No_data') }}</div>
                @endif
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

        input:checked+.slider {
            background-color: #28a745;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #28a745;
        }

        input:checked+.slider:before {
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
                var alertHtml = '<div class="alert ' + alertClass +
                    ' alert-fixed alert-dismissible fade show" role="alert">' +
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
                var productId = $(this).data('id');
                var isChecked = $(this).is(':checked');
                var toggle = $(this);

                // Disable the toggle while processing
                toggle.prop('disabled', true);

                // Get the correct URL based on your route structure
                var url = '{{ route('products.toggleStatus', ':id') }}';
                url = url.replace(':id', productId);

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
                        showNotification('An error occurred while updating the status',
                        'error');
                    },
                    complete: function() {
                        // Re-enable the toggle
                        toggle.prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endsection
