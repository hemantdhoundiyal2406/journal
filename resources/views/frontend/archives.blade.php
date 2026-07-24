@extends('layouts.frontend')

@section('title', 'Archives | IJASER Journal')

@section('content')
<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <h1 class="h2 fw-bold text-dark serif-font mb-1"><i class="bi bi-archive-fill text-primary me-2"></i> Journal Archives</h1>
        <p class="text-muted mb-0">Complete list of published volumes and issues since inception.</p>
    </div>
</div>

<div class="container pb-5">
    <div class="d-flex flex-column gap-4">
        @forelse($volumes as $volume)
            <div class="card journal-card p-4">
                <h3 class="h4 fw-bold text-dark serif-font mb-2">Volume {{ $volume->volume_number }} ({{ $volume->year }})</h3>
                <p class="text-muted small mb-3">{{ $volume->description ?? 'Published articles in Volume ' . $volume->volume_number }}</p>

                <div class="row row-cols-1 row-cols-md-3 g-3">
                    @foreach($volume->issues as $issue)
                        <div class="col">
                            <div class="bg-light p-3 rounded-3 border">
                                <div class="fw-bold text-dark mb-1">Issue {{ $issue->issue_number }} ({{ $issue->publication_month }} {{ $issue->publication_year }})</div>
                                <div class="small text-muted mb-2">{{ $issue->articles_count }} Published Articles</div>
                                <a href="{{ route('issue.detail', $issue->id) }}" class="btn btn-sm btn-outline-primary w-100">
                                    Browse Issue &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="alert alert-info">No archived volumes found.</div>
        @endforelse
    </div>
</div>
@endsection
