@extends('layouts.frontend')

@section('title', 'Submission Successful | IJASER Journal')

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="bg-white p-5 rounded-4 border shadow-lg">
                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                    <i class="bi bi-check-lg fs-1"></i>
                </div>

                <h1 class="h2 fw-bold text-dark serif-font mb-2">Manuscript Successfully Submitted!</h1>
                <p class="text-secondary mb-4">Your research paper has been received by the Editorial Office.</p>

                <div class="bg-light p-4 rounded-3 border mb-4">
                    <div class="small text-uppercase fw-bold text-muted mb-1">Your Assigned Manuscript ID</div>
                    <div class="display-6 fw-bold text-primary font-monospace">{{ $article->manuscript_id }}</div>
                    <div class="small text-muted mt-2">Please save this Manuscript ID for future correspondence.</div>
                </div>

                <h5 class="fw-bold mb-2">Paper Title:</h5>
                <p class="text-dark mb-4">"{{ $article->title }}"</p>

                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4 rounded-pill">Return to Home</a>
                    <a href="{{ route('submission.form') }}" class="btn btn-primary px-4 rounded-pill">Submit Another Paper</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
