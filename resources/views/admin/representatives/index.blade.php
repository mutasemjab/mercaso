@extends('layouts.admin')
@section('title')
{{ __('messages.representatives') }}
@endsection



@section('content')



<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> {{ __('messages.representatives') }} </h3>
        <a href="{{ route('representatives.create') }}" class="btn btn-sm btn-success"> {{ __('messages.New') }} {{
            __('messages.representatives') }}</a>

    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">


            </div>

        </div>
        <div class="clearfix"></div>

        <div id="ajax_responce_serarchDiv" class="col-md-12">
            @can('representative-table')
            @if (@isset($data) && !@empty($data) && count($data) > 0)
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">


                    <th>{{ __('messages.Name') }}</th>
                    <th>{{ __('messages.Phone') }}</th>

                    <th></th>
                </thead>
                <tbody>
                    @foreach ($data as $info)
                    <tr>


                        <td>{{ $info->name }}</td>
                        <td>{{ $info->phone }}</td>


                        <td>
                            @can('representative-edit')
                            <a href="{{ route('representatives.edit', $info->id) }}" class="btn btn-sm  btn-primary">{{
                                __('messages.Edit') }}</a>
                            @endcan
                            {{-- @can('representative-delete')
                            <form action="{{ route('representatives.destroy', $info->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">{{ __('messages.Delete') }}</button>
                            </form>
                            @endcan --}}

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
<script src="{{ asset('assets/admin/js/sliderss.js') }}"></script>
@endsection
