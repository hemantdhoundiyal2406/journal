@extends('layouts.admin')

@section('title', "Author Profile - {$author->full_name}")
@section('page_header', "Author Profile: {$author->full_name}")

@section('content')
<div class="card bg-white border-0 shadow-sm rounded-4 p-4 mb-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h3 class="fw-bold text-dark mb-1">{{ $author->full_name }}</h3>
            <p class="text-secondary mb-1"><i class="bi bi-envelope me-1"></i> {{ $author->email }}</p>
            <p class="text-muted small mb-0"><i class="bi bi-building me-1"></i> {{ $author->institution }}, {{ $author->country }}</p>
        </div>
        <div class="col-md-4 text-md-end">
            <span class="badge bg-primary fs-6 px-3 py-2">Total Submissions: {{ $articleAuthors->count() }}</span>
        </div>
    </div>
</div>

<h5 class="fw-bold mb-3">Authored Articles & Submissions</h5>
<div class="card bg-white border-0 shadow-sm rounded-4 p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th>Manuscript ID</th>
                    <th>Title</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($articleAuthors as $aa)
                    <tr>
                        <td><strong class="font-monospace text-primary">{{ $aa->article?->manuscript_id }}</strong></td>
                        <td class="fw-bold text-dark">{{ $aa->article?->title }}</td>
                        <td>
                            @if($aa->is_corresponding)
                                <span class="badge bg-primary">Corresponding</span>
                            @else
                                <span class="badge bg-light text-dark border">Co-Author</span>
                            @endif
                        </td>
                        <td><span class="badge bg-secondary">{{ $aa->article?->status }}</span></td>
                        <td>
                            @if($aa->article)
                                <a href="{{ route('admin.articles.show', $aa->article->id) }}" class="btn btn-sm btn-outline-dark">Manage Article</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
