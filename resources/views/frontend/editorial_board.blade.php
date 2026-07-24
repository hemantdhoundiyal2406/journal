@extends('layouts.frontend')

@section('title', 'International Editorial Board | IJASER Journal')

@section('content')
<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <h1 class="h2 fw-bold text-dark serif-font mb-1"><i class="bi bi-people-fill text-primary me-2"></i> International Editorial Board</h1>
        <p class="text-muted mb-0">Meet the distinguished global editors and peer reviewers presiding over IJASER academic publications.</p>
    </div>
</div>

<div class="container pb-5">
    @forelse($members as $designation => $group)
        <h3 class="serif-font fw-bold text-primary mb-3 border-bottom pb-2 mt-4">{{ $designation }}</h3>
        <div class="row row-cols-1 row-cols-md-3 g-4 mb-4">
            @foreach($group as $member)
                <div class="col">
                    <div class="card journal-card h-100 p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="bg-light rounded-circle text-primary fw-bold d-flex align-items-center justify-content-center border" style="width: 56px; height: 56px;">
                                <i class="bi bi-person-fill fs-2"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0 text-dark">{{ $member->name }}</h5>
                                <div class="small text-primary fw-semibold">{{ $member->designation }}</div>
                            </div>
                        </div>
                        <p class="small text-secondary mb-2"><i class="bi bi-building me-1"></i> {{ $member->university }}</p>
                        <p class="small text-muted mb-3"><i class="bi bi-geo-alt me-1"></i> {{ $member->country }}</p>
                        @if($member->biography)
                            <p class="small text-muted mb-3">{{ $member->biography }}</p>
                        @endif
                        <div class="mt-auto pt-2 border-top d-flex gap-2">
                            @if($member->orcid)
                                <a href="https://orcid.org/{{ $member->orcid }}" target="_blank" class="btn btn-sm btn-outline-success"><i class="bi bi-person-badge"></i> ORCID</a>
                            @endif
                            @if($member->google_scholar)
                                <a href="{{ $member->google_scholar }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-google"></i> Scholar</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @empty
        <div class="alert alert-info">No editorial members found.</div>
    @endforelse
</div>
@endsection
