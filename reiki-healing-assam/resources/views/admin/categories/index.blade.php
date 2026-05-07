@extends('adminlte::page')

@section('title', 'Categories')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Categories</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add Category
        </a>
    </div>
@stop

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover table-sm mb-0">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Subcategories</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ Str::limit($category->description, 60) ?? '—' }}</td>
                    <td>
                        @if($category->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $category->subcategories_count }}</td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-xs btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this category?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-3">No categories found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop

@push('js')
<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">@csrf</form>
@endpush
