@extends('layouts.admin')
@section('title')
{{ __('messages.dashboard') }}
@endsection

@section('contentheader')
{{ __('messages.dashboard') }}
@endsection

@section('contentheaderlink')
<a href="{{ route('admin.dashboard') }}"> {{ __('messages.dashboard') }} </a>
@endsection

@section('contentheaderactive')
{{ __('messages.view') }}
@endsection



@section('content')
<div class="row" style="background-image: url({{ asset('assets/admin/imgs/dash.jpg') }}) ;background-size:cover;background-repeate:ni-repeate; min-height:600px;">

</div>


@endsection



