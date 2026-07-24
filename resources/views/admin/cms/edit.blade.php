@extends('layouts.admin')

@section('title', "Edit Page - {$page->title}")
@section('page_header', "Edit CMS Page: {$page->title}")

@section('content')
<div class="card bg-white border-0 shadow-sm rounded-4 p-4">
    <form action="{{ route('admin.cms.update', $page->id) }}" method="POST">
        @csrf
        <div class="row g-3 mb-3">
            <div class="col-md-8">
                <label class="form-label fw-semibold">Page Title</label>
                <input type="text" name="title" value="{{ $page->title }}" class="form-control" required>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ $page->is_active ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="is_active">Page Active on Website</label>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Meta Title (SEO)</label>
                <input type="text" name="meta_title" value="{{ $page->meta_title }}" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Meta Keywords</label>
                <input type="text" name="meta_keywords" value="{{ $page->meta_keywords }}" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Meta Description</label>
                <input type="text" name="meta_description" value="{{ $page->meta_description }}" class="form-control">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Page Content (HTML Supported)</label>
            <textarea name="content" rows="12" class="form-control font-monospace" required>{{ $page->content }}</textarea>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.cms.index') }}" class="btn btn-outline-secondary">Back to List</a>
            <button type="submit" class="btn btn-primary fw-bold px-4">Save Page Changes</button>
        </div>
    </form>
</div>
@endsection
