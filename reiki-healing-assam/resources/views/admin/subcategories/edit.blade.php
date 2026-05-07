@extends('adminlte::page')

@section('title', 'Edit Subcategory')

@section('content_header')
    <h1>Edit Subcategory — {{ $subcategory->name }}</h1>
@stop

@section('content')
<div class="card col-md-7">
    <div class="card-body">
        <form action="{{ route('admin.subcategories.update', $subcategory) }}" method="POST">
            @csrf @method('PUT')
            @include('admin.subcategories._form', ['subcategory' => $subcategory])
            <button type="submit" class="btn btn-primary">Update Subcategory</button>
            <a href="{{ route('admin.subcategories.index') }}" class="btn btn-secondary ml-2">Cancel</a>
        </form>
    </div>
</div>
@stop

@push('js')
<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">@csrf</form>
@endpush
