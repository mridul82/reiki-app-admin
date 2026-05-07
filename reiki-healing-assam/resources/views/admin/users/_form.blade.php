<div class="form-group">
    <label>Name</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $user->name ?? '') }}" required>
    @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label>Email</label>
    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
           value="{{ old('email', $user->email ?? '') }}" required>
    @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label>Phone</label>
    <input type="text" name="phone" class="form-control"
           value="{{ old('phone', $user->phone ?? '') }}">
</div>

<div class="form-group">
    <label>Subscription Expires At</label>
    <input type="date" name="subscription_expires_at" class="form-control @error('subscription_expires_at') is-invalid @enderror"
           value="{{ old('subscription_expires_at', isset($user->subscription_expires_at) ? \Carbon\Carbon::parse($user->subscription_expires_at)->format('Y-m-d') : '') }}">
    @error('subscription_expires_at')<span class="invalid-feedback">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1"
               {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
        <label class="custom-control-label" for="is_active">Account Active</label>
    </div>
</div>
