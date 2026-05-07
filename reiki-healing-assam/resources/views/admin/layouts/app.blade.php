@extends('adminlte::page')

@section('content_header')
    <h1>@yield('page_title', 'Dashboard')</h1>
@stop

@section('footer')
    <strong>Reiki Healing Assam</strong> &mdash; Developed by <strong>ITNext Solutions</strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.0.0
    </div>
@stop

@push('js')
<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">@csrf</form>
@endpush
