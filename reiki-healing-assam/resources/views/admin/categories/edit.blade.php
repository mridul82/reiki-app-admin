@extends('adminlte::page')

@section('title', 'Edit Category')

@section('content_header')
    <h1>Edit Category — {{ $category->name }}</h1>
@stop

@section('content')
<div class="card col-md-7">
    <div class="card-body">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf @method('PUT')
            @include('admin.categories._form', ['category' => $category])
            <button type="submit" class="btn btn-primary">Update Category</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary ml-2">Cancel</a>
        </form>
    </div>
</div>
@stop

@push('js')
<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">@csrf</form>
@endpush
