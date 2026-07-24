@extends('layouts.frontend')

@section('title', "Volume {$issue->volume?->volume_number}, Issue {$issue->issue_number} | IJASER Journal")

@section('content')
<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <span class="badge bg-primary px-3 py-2 rounded-pill mb-2">Volume {{ $issue->volume?->volume_number }}, Issue {{ $issue->issue_number }} ({{ $issue->publication_year }})</span>
        <h1 class="h2 fw-bold text-dark serif-font mb-1">{{ $issue->title ?? 'Issue Table of Contents' }}</h1>
    </div>
</div>

<div class="container pb-5">
    <h3 class="serif-font fw-bold text-dark mb-3">Published Articles in this Issue</h3>

    <div class="d-flex flex-column gap-3">
        @forelse($issue->articles as $article)
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
                    <a href="{{ route('article.detail', $article->id) }}" class="btn btn-sm btn-outline-primary">Read Article &rarr;</a>
                </div>
            </div>
        @empty
            <div class="alert alert-info">No articles published in this issue.</div>
        @endforelse
    </div>
</div>
@endsection
