@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['clients'] }}</h3>
                <p>Total Clients</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <a href="{{ route('admin.users.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['active_clients'] }}</h3>
                <p>Active Subscriptions</p>
            </div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <a href="{{ route('admin.users.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['categories'] }}</h3>
                <p>Categories</p>
            </div>
            <div class="icon"><i class="fas fa-folder"></i></div>
            <a href="{{ route('admin.categories.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['solutions'] }}</h3>
                <p>Solutions</p>
            </div>
            <div class="icon"><i class="fas fa-lightbulb"></i></div>
            <a href="{{ route('admin.solutions.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box" style="background:#6f42c1;color:#fff;">
            <div class="inner">
                <h3>{{ $stats['reports'] }}</h3>
                <p>Reports Generated</p>
            </div>
            <div class="icon"><i class="fas fa-file-alt"></i></div>
            <a href="{{ route('admin.reports.index') }}" class="small-box-footer" style="background:rgba(0,0,0,0.1);">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Clients</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Expires</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_clients as $client)
                        <tr>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->email }}</td>
                            <td>
                                @if($client->hasActiveSubscription())
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $client->subscription_expires_at ? \Carbon\Carbon::parse($client->subscription_expires_at)->format('d M Y') : '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted">No clients yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Categories Overview</h3>
            </div>
            <div class="card-body">
                @forelse($categories_overview as $cat)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span><i class="fas fa-circle text-primary mr-2"></i>{{ $cat->name }}</span>
                    <span class="badge badge-primary">{{ $cat->subcategories_count }} subcategories</span>
                </div>
                @empty
                <p class="text-muted mb-0">No categories yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@stop

@push('js')
<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">@csrf</form>
@endpush
