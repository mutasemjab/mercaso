@extends('layouts.admin')
@section('title')
{{ __('messages.brands') }}
@endsection



@section('content')



<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> {{ __('messages.brands') }} </h3>
        <a href="{{ route('brands.create') }}" class="btn btn-sm btn-success"> {{ __('messages.New') }} {{
            __('messages.brands') }}</a>

    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">


            </div>

        </div>
        <div class="clearfix"></div>

        <div id="ajax_responce_serarchDiv" class="col-md-12">
            @can('brand-table')
            @if (@isset($data) && !@empty($data) && count($data) > 0)
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">


                    <th>{{ __('messages.Name') }}</th>
                    <th>{{ __('messages.photo') }}</th>

                    <th></th>
                </thead>
                <tbody>
                    @foreach ($data as $info)
                    <tr>


                        <td>{{ $info->name }}</td>
                        <td>
                            <div class="image">
                                <img class="custom_img" src="{{ asset('assets/admin/uploads') . '/' . $info->photo }}">

                            </div>
                        </td>


                        <td>
                            @can('brand-edit')
                            <a href="{{ route('brands.edit', $info->id) }}" class="btn btn-sm  btn-primary">{{
                                __('messages.Edit') }}</a>
                            @endcan
                            @can('brand-delete')
                          @can('brand-delete')
                            <form action="{{ route('brands.destroy', $info->id) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger btn-delete">{{ __('messages.Delete') }}</button>
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
                {{ __('messages.No_data') }} </div>
            @endif
            @endcan

        </div>



    </div>

</div>

</div>

@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Handle delete confirmation
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const form = this.closest('form');

            // Show native confirm dialog
            if (confirm("{{ __('messages.Are_you_sure_delete') }}")) {
                form.submit();
            }
        });
    });
});
</script>
@endsection
