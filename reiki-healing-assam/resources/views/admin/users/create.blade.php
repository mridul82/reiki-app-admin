@extends('adminlte::page')

@section('title', 'Add Client')

@section('content_header')
    <h1>Add Client</h1>
@stop

@section('content')
<div class="card col-md-8">
    <div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            @include('admin.users._form')
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Create Client</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ml-2">Cancel</a>
        </form>
    </div>
</div>
@stop

@push('js')
<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">@csrf</form>
@endpush
