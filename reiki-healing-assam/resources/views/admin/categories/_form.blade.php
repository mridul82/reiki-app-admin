<div class="form-group">
    <label>Name</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $category->name ?? '') }}" required>
    @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label>Description <small class="text-muted">(optional)</small></label>
    <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description ?? '') }}</textarea>
</div>

<div class="form-group">
    <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1"
               {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
        <label class="custom-control-label" for="is_active">Active</label>
    </div>
</div>
