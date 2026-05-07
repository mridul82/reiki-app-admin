@extends('adminlte::page')

@section('title', 'Add Subcategory')

@section('content_header')
    <h1>Add Subcategory</h1>
@stop

@section('content')
<div class="card col-md-7">
    <div class="card-body">
        <form action="{{ route('admin.subcategories.store') }}" method="POST">
            @csrf
            @include('admin.subcategories._form')
            <button type="submit" class="btn btn-primary">Create Subcategory</button>
            <a href="{{ route('admin.subcategories.index') }}" class="btn btn-secondary ml-2">Cancel</a>
        </form>
    </div>
</div>
@stop

@push('js')
<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">@csrf</form>
@endpush
