@extends('adminlte::page')

@section('title', 'Add Remedy Type')

@section('content_header')
    <h1>Add Remedy Type</h1>
@stop

@section('content')
<div class="card" style="max-width:500px;">
    <div class="card-body">
        <form action="{{ route('admin.remedy-types.store') }}" method="POST">
            @csrf
            @include('admin.remedy_types._form')
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('admin.remedy-types.index') }}" class="btn btn-secondary ml-2">Cancel</a>
        </form>
    </div>
</div>
@stop
