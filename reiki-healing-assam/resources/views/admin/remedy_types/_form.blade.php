<div class="form-group">
    <label>Name</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $remedyType->name ?? '') }}" required>
    @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label>Sort Order</label>
    <input type="number" name="sort_order" class="form-control" style="width:120px;"
           value="{{ old('sort_order', $remedyType->sort_order ?? 0) }}" min="0">
</div>

<div class="form-group">
    <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1"
               {{ old('is_active', $remedyType->is_active ?? true) ? 'checked' : '' }}>
        <label class="custom-control-label" for="is_active">Active</label>
    </div>
</div>
