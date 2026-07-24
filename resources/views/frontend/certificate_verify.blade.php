@extends('layouts.frontend')

@section('title', 'Certificate Verification | IJASER Journal')

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="bg-white p-5 rounded-4 border shadow-lg">
                @if($article)
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-shield-check fs-1"></i>
                    </div>

                    <h1 class="h3 fw-bold text-dark serif-font mb-2">Valid Official Certificate</h1>
                    <span class="badge bg-success px-3 py-2 rounded-pill mb-4">Verification Token: {{ $token }}</span>

                    <div class="bg-light p-4 rounded-3 border text-start mb-4">
                        <div class="row g-2 small">
                            <div class="col-sm-4 text-muted fw-semibold">Manuscript ID:</div>
                            <div class="col-sm-8 fw-bold text-dark">{{ $article->manuscript_id }}</div>

                            <div class="col-sm-4 text-muted fw-semibold">Article Title:</div>
                            <div class="col-sm-8 fw-bold text-primary">{{ $article->title }}</div>

                            <div class="col-sm-4 text-muted fw-semibold">Authors:</div>
                            <div class="col-sm-8 text-dark">{{ $article->formatted_authors }}</div>

                            <div class="col-sm-4 text-muted fw-semibold">Issue:</div>
                            <div class="col-sm-8 text-dark">Volume {{ $article->volume?->volume_number }}, Issue {{ $article->issue?->issue_number }}</div>

                            <div class="col-sm-4 text-muted fw-semibold">Publication Date:</div>
                            <div class="col-sm-8 text-dark">{{ $article->published_at?->format('F d, Y') ?? 'Published' }}</div>
                        </div>
                    </div>

                    <a href="{{ route('article.detail', $article->id) }}" class="btn btn-primary px-4 rounded-pill">
                        View Published Article Page &rarr;
                    </a>
                @else
                    <div class="bg-danger text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="bi bi-x-lg fs-1"></i>
                    </div>
                    <h1 class="h3 fw-bold text-dark serif-font mb-2">Invalid Certificate Token</h1>
                    <p class="text-secondary mb-4">No published record was found matching token "<strong>{{ $token }}</strong>". Please double check the QR code or URL.</p>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4 rounded-pill">Return to Home</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
