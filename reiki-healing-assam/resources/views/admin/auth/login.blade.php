@extends('adminlte::auth.login')

@section('auth_header', 'Reiki Healing Assam')
@section('auth_subheader', 'Admin Panel — Sign in to continue')

@section('auth_body')
<form action="{{ route('admin.login.post') }}" method="POST">
    @csrf

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ $errors->first() }}
        </div>
    @endif

    <div class="input-group mb-3">
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               placeholder="Email" value="{{ old('email') }}" autofocus required>
        <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
        </div>
        @error('email')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="input-group mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
        </div>
    </div>
</form>
@endsection
