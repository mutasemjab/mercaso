@extends('layouts.admin')
@section('title')
    {{ __('messages.units') }}
@endsection

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.units') }} </h3>
            <a href="{{ route('units.create') }}" class="btn btn-sm btn-success">
                {{ __('messages.New') }} {{ __('messages.units') }}
            </a>
        </div>

        <div class="card-body">
            <div class="clearfix"></div>

            <div id="ajax_responce_serarchDiv" class="col-md-12">
                @can('unit-table')
                    @if (@isset($data) && !@empty($data) && count($data) > 0)
                        <table id="example2" class="table table-bordered table-hover">
                            <thead class="custom_thead">
                                <th>{{ __('messages.ID') }}</th>
                                <th>{{ __('messages.Name_en') }}</th>
                                <th>{{ __('messages.Name_ar') }}</th>
                                <th></th>
                            </thead>
                            <tbody>
                                @foreach ($data as $info)
                                    <tr>
                                        <td>{{ $info->id }}</td>
                                        <td>{{ $info->name_en }}</td>
                                        <td>{{ $info->name_ar }}</td>

                                        <td>
                                            @can('unit-edit')
                                                <a href="{{ route('units.edit', $info->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    {{ __('messages.Edit') }}
                                                </a>
                                            @endcan

                                            @can('unit-delete')
                                                <form action="{{ route('units.destroy', $info->id) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger btn-delete">
                                                        {{ __('messages.Delete') }}
                                                    </button>
                                                </form>
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
                            {{ __('messages.No_data') }}
                        </div>
                    @endif
                @endcan
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Confirmation before deleting
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    if (confirm("{{ __('messages.Are_you_sure_delete') }}")) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
