@extends('adminlte::page')

@section('title', 'Edit Solution')

@section('content_header')
    <h1>Edit Solution — {{ $solution->title }}</h1>
@stop

@section('content')
<div class="card col-md-8">
    <div class="card-body">
        <form action="{{ route('admin.solutions.update', $solution) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('admin.solutions._form', ['solution' => $solution])
            <button type="submit" class="btn btn-primary">Update Solution</button>
            <a href="{{ route('admin.solutions.index') }}" class="btn btn-secondary ml-2">Cancel</a>
        </form>
    </div>
</div>
@stop

@push('js')
<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">@csrf</form>
<script src="https://cdn.tiny.cloud/1/xysfj82xey8ykspdbd3fqn6y41p21rb94h16of8lc93gejrk/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#solution_content',
    height: 400,
    menubar: false,
    plugins: 'lists link',
    toolbar: 'undo redo | bold italic underline | forecolor backcolor | alignleft aligncenter alignright | bullist numlist | removeformat',
    content_style: 'body { font-family: Segoe UI, Arial, sans-serif; font-size: 14px; }',
    setup: function(editor) {
        editor.on('change', function() { editor.save(); });
    }
});
</script>
@endpush
