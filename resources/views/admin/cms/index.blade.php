@extends('layouts.admin')

@section('title', 'CMS Pages Management')
@section('page_header', 'Content Management System (CMS Pages)')

@section('content')
<div class="card bg-white border-0 shadow-sm rounded-4 p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th>Page Title</th>
                    <th>Slug</th>
                    <th>Meta Title</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pages as $page)
                    <tr>
                        <td class="fw-bold text-dark">{{ $page->title }}</td>
                        <td><code class="text-primary">/page/{{ $page->slug }}</code></td>
                        <td class="small text-muted">{{ $page->meta_title ?? 'Default' }}</td>
                        <td>
                            @if($page->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Disabled</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.cms.edit', $page->id) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil-square me-1"></i> Edit Page
                            </a>
                            <a href="{{ route('cms.page', $page->slug) }}" target="_blank" class="btn btn-sm btn-outline-dark ms-1">
                                Preview
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
