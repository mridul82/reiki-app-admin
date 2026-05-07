@extends('adminlte::page')

@section('title', 'Edit Remedy Type')

@section('content_header')
    <h1>Edit Remedy Type</h1>
@stop

@section('content')
<div class="card" style="max-width:500px;">
    <div class="card-body">
        <form action="{{ route('admin.remedy-types.update', $remedyType) }}" method="POST">
            @csrf @method('PUT')
            @include('admin.remedy_types._form', ['remedyType' => $remedyType])
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.remedy-types.index') }}" class="btn btn-secondary ml-2">Cancel</a>
        </form>
    </div>
</div>
@stop
