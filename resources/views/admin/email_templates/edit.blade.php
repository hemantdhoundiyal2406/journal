@extends('layouts.admin')

@section('title', "Edit Template - {$template->name}")
@section('page_header', "Edit Email Template: {$template->name}")

@section('content')
<div class="card bg-white border-0 shadow-sm rounded-4 p-4">
    <div class="alert alert-info small mb-3">
        <i class="bi bi-info-circle-fill me-1"></i> Available Dynamic Placeholders: <code>{{ $template->placeholders }}</code>
    </div>

    <form action="{{ route('admin.email-templates.update', $template->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-semibold">Subject Line</label>
            <input type="text" name="subject" value="{{ $template->subject }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Email Body HTML</label>
            <textarea name="body_html" rows="10" class="form-control font-monospace" required>{{ $template->body_html }}</textarea>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.email-templates.index') }}" class="btn btn-outline-secondary">Back to Templates</a>
            <button type="submit" class="btn btn-primary fw-bold px-4">Save Template</button>
        </div>
    </form>
</div>
@endsection
