@extends('adminlte::page')

@section('title', 'Edit Client')

@section('content_header')
    <h1>Edit Client — {{ $user->name }}</h1>
@stop

@section('content')
<div class="card col-md-8">
    <div class="card-body">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf @method('PUT')
            @include('admin.users._form', ['user' => $user])
            <div class="form-group">
                <label>New Password <small class="text-muted">(leave blank to keep current)</small></label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Update Client</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ml-2">Cancel</a>
        </form>
    </div>
</div>
@stop

@push('js')
<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">@csrf</form>
@endpush
