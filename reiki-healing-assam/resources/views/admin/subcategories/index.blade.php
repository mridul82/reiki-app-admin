@extends('adminlte::page')

@section('title', 'Subcategories')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Subcategories</h1>
        <a href="{{ route('admin.subcategories.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add Subcategory
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
                    <th>Category</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Solutions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subcategories as $sub)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $sub->category->name }}</td>
                    <td>{{ $sub->name }}</td>
                    <td>{{ Str::limit($sub->description, 50) ?? '—' }}</td>
                    <td>
                        @if($sub->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>{{ $sub->solutions_count }}</td>
                    <td>
                        <a href="{{ route('admin.subcategories.edit', $sub) }}" class="btn btn-xs btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.subcategories.destroy', $sub) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this subcategory?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-3">No subcategories found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop

@push('js')
<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">@csrf</form>
@endpush
