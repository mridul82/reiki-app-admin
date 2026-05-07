@extends('adminlte::page')

@section('title', 'Solutions')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Solutions</h1>
        <a href="{{ route('admin.solutions.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add Solution
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
    <div class="card-body">
        <table id="solutions-table" class="table table-bordered table-hover table-sm w-100">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Subcategory</th>
                    <th>Remedy Type</th>
                    <th>Sort</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($solutions as $solution)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($solution->image_path)
                            <img src="{{ Storage::disk('uploads')->url($solution->image_path) }}" alt="" style="height:36px;width:36px;object-fit:cover;border-radius:4px;">
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>{{ $solution->title }}</td>
                    <td>{{ $solution->subcategory->category->name }}</td>
                    <td>{{ $solution->subcategory->name }}</td>
                    <td><span class="badge badge-info">{{ $solution->remedy_type }}</span></td>
                    <td>{{ $solution->sort_order }}</td>
                    <td>
                        @if($solution->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-secondary">Inactive</span>
                        @endif
                    </td>
                    <td class="text-nowrap">
                        <a href="{{ route('admin.solutions.show', $solution) }}" class="btn btn-xs btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.solutions.edit', $solution) }}" class="btn btn-xs btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.solutions.destroy', $solution) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this solution?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop

@push('css')
<link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@endpush

@push('js')
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function () {
    $('#solutions-table').DataTable({
        pageLength: 25,
        order: [[3, 'asc'], [4, 'asc'], [6, 'asc']],
        columnDefs: [
            { orderable: false, targets: [1, 8] },
            { searchable: false, targets: [1, 6, 8] }
        ],
        language: {
            search: 'Search:',
            lengthMenu: 'Show _MENU_ entries',
        }
    });
});
</script>
<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">@csrf</form>
@endpush
