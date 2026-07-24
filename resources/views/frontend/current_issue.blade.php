@extends('layouts.frontend')

@section('title', 'Current Issue | IJASER Journal')

@section('content')
<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <h1 class="h2 fw-bold text-dark serif-font mb-1"><i class="bi bi-journal-album text-primary me-2"></i> Current Issue</h1>
        <p class="text-muted mb-0">Browse published research papers in the latest issue of IJASER.</p>
    </div>
</div>

<div class="container pb-5">
    @if($currentIssue)
        <div class="card journal-card p-4 mb-4">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <span class="badge bg-primary px-3 py-2 rounded-pill mb-2">Volume {{ $currentIssue->volume?->volume_number }}, Issue {{ $currentIssue->issue_number }} ({{ $currentIssue->publication_year }})</span>
                    <h2 class="h3 fw-bold text-dark serif-font mb-2">{{ $currentIssue->title ?? 'Current Issue Table of Contents' }}</h2>
                    <p class="text-muted mb-0"><i class="bi bi-calendar3 me-1"></i> Publication Date: {{ $currentIssue->published_at?->format('F d, Y') ?? 'Published' }}</p>
                </div>
            </div>
        </div>

        <h3 class="serif-font fw-bold text-dark mb-3">Table of Contents</h3>

        <div class="d-flex flex-column gap-3">
            @forelse($currentIssue->articles as $article)
                <div class="card journal-card p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge badge-category">{{ $article->category }}</span>
                        <span class="small text-muted">Pages: {{ $article->start_page }}-{{ $article->end_page }}</span>
                    </div>
                    <h4 class="h5 fw-bold mb-2">
                        <a href="{{ route('article.detail', $article->id) }}" class="text-dark text-decoration-none hover-primary">
                            {{ $article->title }}
                        </a>
                    </h4>
                    <div class="small text-secondary mb-2">Authors: {{ $article->formatted_authors }}</div>
                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                        <span class="small text-muted"><i class="bi bi-link-45deg"></i> DOI: {{ $article->doi ?? 'Pending' }}</span>
                        <a href="{{ route('article.detail', $article->id) }}" class="btn btn-sm btn-outline-primary">View Article &rarr;</a>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">No articles published in current issue.</div>
            @endforelse
        </div>
    @else
        <div class="alert alert-warning">No current issue published yet.</div>
    @endif
</div>
@endsection
