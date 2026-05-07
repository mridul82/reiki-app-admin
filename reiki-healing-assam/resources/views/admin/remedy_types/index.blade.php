@extends('adminlte::page')

@section('title', 'Remedy Types')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Remedy Types</h1>
        <a href="{{ route('admin.remedy-types.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add Remedy Type
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
                    <th>Sort Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($remedyTypes as $type)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $type->name }}</td>
                    <td>{{ $type->sort_order }}</td>
                    <td>
                        @if($type->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.remedy-types.edit', $type) }}" class="btn btn-xs btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.remedy-types.destroy', $type) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this remedy type?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-3">No remedy types found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop
