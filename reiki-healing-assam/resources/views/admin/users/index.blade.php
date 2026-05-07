@extends('adminlte::page')

@section('title', 'Clients')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Clients</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add Client
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
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Subscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone ?? '—' }}</td>
                    <td>
                        @if($user->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>
                        @if($user->subscription_expires_at)
                            @if(\Carbon\Carbon::parse($user->subscription_expires_at)->isFuture())
                                <span class="text-success">{{ \Carbon\Carbon::parse($user->subscription_expires_at)->format('d M Y') }}</span>
                            @else
                                <span class="text-danger">Expired {{ \Carbon\Carbon::parse($user->subscription_expires_at)->format('d M Y') }}</span>
                            @endif
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-xs btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this client?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-3">No clients found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="card-footer">
        {{ $users->links() }}
    </div>
    @endif
</div>
@stop

@push('js')
<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">@csrf</form>
@endpush
