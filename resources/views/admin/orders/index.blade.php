@extends('layouts.admin')
@section('title')
    {{ __('messages.orders') }}
@endsection


@section('contentheaderactive')
    {{ __('messages.Show') }}
@endsection



@section('content')



    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.orders') }}
            </h3>
            <input type="hidden" id="token_search" value="{{ csrf_token() }}">

            <a href="{{ route('orders.create') }}" class="btn btn-sm btn-success"> {{ __('messages.New') }}
                {{ __('messages.orders') }}</a>
        </div>

        <!-- Filter Section -->
        <div class="card-body border-top">
            <form action="{{ route('orders.index') }}" method="GET" class="form-inline justify-content-center mb-3">
                <div class="form-group mr-3 mb-2">
                    <label for="search" class="mr-2"><strong>{{ __('messages.Search') }}:</strong></label>
                    <input type="text" name="search" id="search" class="form-control"
                           placeholder="Order # or Phone" value="{{ request('search') }}">
                </div>

                <div class="form-group mr-3 mb-2">
                    <label for="from_date" class="mr-2"><strong>From Date:</strong></label>
                    <input type="date" name="from_date" id="from_date" class="form-control"
                           value="{{ request('from_date') }}">
                </div>

                <div class="form-group mr-3 mb-2">
                    <label for="to_date" class="mr-2"><strong>To Date:</strong></label>
                    <input type="date" name="to_date" id="to_date" class="form-control"
                           value="{{ request('to_date') }}">
                </div>

                <button type="submit" class="btn btn-primary mb-2">
                    <i class="fas fa-filter mr-1"></i>{{ __('messages.Search') }}
                </button>

                <a href="{{ route('orders.index') }}" class="btn btn-secondary ml-2 mb-2">
                    <i class="fas fa-redo mr-1"></i>Reset
                </a>
            </form>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">

                    {{-- <input  type="radio" name="searchbyradio" id="searchbyradio" value="name"> name --}}

                    {{-- <input autofocus style="margin-top: 6px !important;" type="text" id="search_by_text" placeholder=" name" class="form-control"> <br> --}}

                </div>

            </div>
            <div class="clearfix"></div>

            <div id="ajax_responce_serarchDiv" class="col-md-12">

                @if (isset($data) && !$data->isEmpty())
                  @can('order-table')
                    <table id="example2" class="table table-bordered table-hover">
                        <thead class="custom_thead">
                            <th>{{ __('messages.Order_Number') }}</th>
                            <th>{{ __('messages.Phone') }}</th>
                            <th>Type of delivery</th>
                            <th>{{ __('messages.Date') }}</th>
                            <th>{{ __('messages.order_type') }}</th>
                            <th>{{ __('messages.order_status') }}</th>
                            <th>{{ __('messages.delivery_fee') }}</th>
                            <th>{{ __('messages.total_prices') }}</th>
                            <th>{{ __('messages.total_discount') }}</th>
                            <th>{{ __('messages.payment_type') }}</th>
                            <th>{{ __('messages.payment_status') }}</th>
                            <th>{{ __('messages.Action') }}</th>
                        </thead>
                        <tbody>
                            @foreach ($data as $info)
                                <tr>

                                    <td>{{ $info->number }}</td>
                                    <td>{{ $info->phone_in_order ?? '-' }}</td>
                                    <td>{{ $info->type_delivery == 1 ? 'pickup' : 'delivery' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($info->date)->format('m/d/Y') }}</td>
                                    <td style="{{ $info->order_type == 2 ? 'color:red;' : '' }}">
                                        {{ $info->order_type == 1 ? __('Sell') : __('Refund') }}
                                    </td>

                                    <td>

                                        @if ($info->order_status == 1)
                                            {{ __('messages.Pending') }}
                                        @elseif($info->order_status == 2)
                                            {{ __('messages.Accepted') }}
                                        @elseif($info->order_status == 3)
                                            {{ __('messages.OnTheWay') }}
                                        @elseif($info->order_status == 4)
                                            {{ __('messages.Delivered') }}
                                        @elseif($info->order_status == 5)
                                            {{ __('messages.Canceled') }}
                                        @else
                                            {{ __('messages.Refund') }}
                                        @endif
                                    </td>
                                    <td>${{ $info->delivery_fee }}</td>
                                    <td>${{ $info->total_prices }}</td>
                                    <td>${{ $info->total_discounts }}</td>
                                    <td>{{ $info->payment_type == 1 ? 'cash' : 'visa' }}</td>
                                    <td>
                                        @if ($info->payment_status == 1)
                                            Paid
                                        @else
                                            UnPaid
                                        @endif
                                    </td>


                                    <td>
                                         @can('order-edit')
                                        <a href="{{ route('orders.edit', $info->id) }}" class="btn btn-sm btn-primary">
                                            {{ __('messages.Edit') }}</a>
                                            @endcan
                                             @can('order-table')
                                        <a href="{{ route('orders.show', $info->id) }}" class="btn btn-sm btn-primary">
                                            {{ __('messages.Show') }}</a>
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
                    <div class="alert alert-danger">
                        {{ __('messages.no_data') }}
                    </div>
                @endif

            </div>



        </div>

    </div>

    </div>

@endsection

@section('script')
    <script src="{{ asset('assets/admin/js/orderss.js') }}"></script>
@endsection
