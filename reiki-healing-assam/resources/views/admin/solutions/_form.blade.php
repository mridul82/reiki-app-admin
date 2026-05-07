<div class="form-group">
    <label>Subcategory</label>
    <select id="subcategory_id" name="subcategory_id" class="form-control @error('subcategory_id') is-invalid @enderror" required style="width:100%;">
        <option value="">— Select Subcategory —</option>
        @foreach($subcategories as $sub)
            <option value="{{ $sub->id }}" {{ old('subcategory_id', $solution->subcategory_id ?? '') == $sub->id ? 'selected' : '' }}>
                {{ $sub->category->name }} → {{ $sub->name }}
            </option>
        @endforeach
    </select>
    @error('subcategory_id')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
</div>

@push('css')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css">
@endpush

@push('js')
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(function () {
        $('#subcategory_id').select2({
            theme: 'bootstrap',
            placeholder: '— Select Subcategory —',
            allowClear: true,
            width: '100%',
        });
    });
</script>
@endpush

<div class="form-group">
    <label>Remedy Type</label>
    <select name="remedy_type" class="form-control @error('remedy_type') is-invalid @enderror" required>
        <option value="">— Select Remedy Type —</option>
        @foreach($remedyTypes as $type)
            <option value="{{ $type }}" {{ old('remedy_type', $solution->remedy_type ?? '') == $type ? 'selected' : '' }}>
                {{ $type }}
            </option>
        @endforeach
    </select>
    @error('remedy_type')<span class="invalid-feedback">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label>Title</label>
    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
           value="{{ old('title', $solution->title ?? '') }}" required>
    @error('title')<span class="invalid-feedback">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label>Content</label>
    <textarea id="solution_content" name="content" class="@error('content') is-invalid @enderror" required>{{ old('content', $solution->content ?? '') }}</textarea>
    @error('content')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label>Image <small class="text-muted">(optional, jpg/png, max 2MB)</small></label>
    @if(isset($solution) && $solution->image_path)
        <div class="mb-2">
            <img src="{{ Storage::disk('uploads')->url($solution->image_path) }}" alt="Current image" style="height:80px;border-radius:4px;">
            <div class="custom-control custom-checkbox mt-1">
                <input type="checkbox" class="custom-control-input" id="remove_image" name="remove_image" value="1">
                <label class="custom-control-label text-danger" for="remove_image">Remove current image</label>
            </div>
        </div>
    @endif
    <input type="file" name="image" class="form-control-file @error('image') is-invalid @enderror" accept="image/*">
    @error('image')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
</div>

<div class="form-group">
    <label>Sort Order</label>
    <input type="number" name="sort_order" class="form-control" style="width:120px;"
           value="{{ old('sort_order', $solution->sort_order ?? 0) }}" min="0">
</div>

<div class="form-group">
    <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1"
               {{ old('is_active', $solution->is_active ?? true) ? 'checked' : '' }}>
        <label class="custom-control-label" for="is_active">Active</label>
    </div>
</div>
