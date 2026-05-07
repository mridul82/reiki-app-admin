@extends('adminlte::page')

@section('title', 'Solution Details')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Solution Details</h1>
        <div>
            <a href="{{ route('admin.solutions.edit', $solution) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.solutions.index') }}" class="btn btn-secondary btn-sm ml-1">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">

                <table class="table table-borderless table-sm mb-0">
                    <tr>
                        <th style="width:160px;color:#555;">Category</th>
                        <td>{{ $solution->subcategory->category->name }}</td>
                    </tr>
                    <tr>
                        <th style="color:#555;">Subcategory</th>
                        <td>{{ $solution->subcategory->name }}</td>
                    </tr>
                    <tr>
                        <th style="color:#555;">Remedy Type</th>
                        <td><span class="badge badge-info">{{ $solution->remedy_type }}</span></td>
                    </tr>
                    <tr>
                        <th style="color:#555;">Title</th>
                        <td><strong>{{ $solution->title }}</strong></td>
                    </tr>
                    <tr>
                        <th style="color:#555;">Sort Order</th>
                        <td>{{ $solution->sort_order }}</td>
                    </tr>
                    <tr>
                        <th style="color:#555;">Status</th>
                        <td>
                            @if($solution->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th style="color:#555;">Created</th>
                        <td>{{ $solution->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                    <tr>
                        <th style="color:#555;">Updated</th>
                        <td>{{ $solution->updated_at->format('d M Y, h:i A') }}</td>
                    </tr>
                </table>

                <hr>

                <h6 class="text-muted text-uppercase mb-2" style="font-size:11px;letter-spacing:1px;">Content</h6>
                <div style="font-size:14px;line-height:1.7;background:#f8f9fa;padding:16px;border-radius:6px;border:1px solid #e9ecef;">{!! $solution->content !!}</div>

            </div>
        </div>
    </div>

    <div class="col-md-4">
        @if($solution->image_path)
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Image</h6></div>
            <div class="card-body text-center">
                <img src="{{ Storage::disk('uploads')->url($solution->image_path) }}"
                     alt="{{ $solution->title }}"
                     style="max-width:100%;border-radius:6px;">
            </div>
        </div>
        @endif
    </div>
</div>
@stop
