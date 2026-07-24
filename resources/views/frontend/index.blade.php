@extends('layouts.frontend')

@section('title', \App\Models\JournalSetting::getByKey('meta_title'))

@section('content')
<!-- Hero Banner -->
<section class="hero-banner text-center text-md-start">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark fw-bold px-3 py-2 rounded-pill mb-3 text-uppercase" style="letter-spacing: 0.5px;">
                    <i class="bi bi-star-fill me-1"></i> International Open Access Journal
                </span>
                <h1 class="display-5 fw-bold mb-3 text-white serif-font lh-sm">
                    {{ \App\Models\JournalSetting::getByKey('hero_title', 'Empowering Global Academic Excellence & Innovation') }}
                </h1>
                <p class="lead mb-4 text-light opacity-90 fw-light">
                    {{ \App\Models\JournalSetting::getByKey('hero_subtitle', 'Publishing double-blind peer-reviewed research across Science, Engineering, Medicine, and Multidisciplinary Studies.') }}
                </p>

                <!-- Search Bar -->
                <form action="{{ route('search') }}" method="GET" class="bg-white p-2 rounded-4 shadow-lg d-flex flex-column flex-md-row gap-2 max-w-xl">
                    <div class="input-group input-group-lg border-0">
                        <span class="input-group-text bg-transparent border-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" class="form-control border-0 shadow-none fs-6" placeholder="Search by Article Title, Author Name, Keywords or DOI...">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg px-4 rounded-3 fw-bold text-nowrap">
                        Search Articles
                    </button>
                </form>

                <div class="mt-4 d-flex flex-wrap align-items-center gap-4 text-light small opacity-85">
                    <div><i class="bi bi-lightning-charge-fill text-warning me-1"></i> Rapid 14-Day Review</div>
                    <div><i class="bi bi-award-fill text-info me-1"></i> Crossref DOI Assigned</div>
                    <div><i class="bi bi-check-circle-fill text-success me-1"></i> COPE Ethical Standards</div>
                </div>
            </div>

            <!-- Quick Download / Stat Widget -->
            <div class="col-lg-4 mt-5 mt-lg-0">
                <div class="bg-white text-dark p-4 rounded-4 shadow-lg border border-light">
                    <h5 class="fw-bold mb-3 text-dark serif-font"><i class="bi bi-journal-text text-primary me-2"></i> Call for Manuscripts</h5>
                    <p class="small text-secondary mb-3">Submissions are invited for Volume 12, Issue 3 (2026). Fast-track publishing available upon peer acceptance.</p>
                    
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('submission.form') }}" class="btn btn-primary fw-bold py-2 shadow-sm">
                            <i class="bi bi-cloud-upload-fill me-2"></i> Submit Paper Now
                        </a>
                        <a href="{{ route('cms.page', 'author-guidelines') }}" class="btn btn-outline-secondary btn-sm py-2">
                            <i class="bi bi-download me-1"></i> Download Author Guidelines
                        </a>
                    </div>
                    <div class="small text-center text-muted border-top pt-2">
                        <i class="bi bi-clock-history me-1"></i> Next Issue Release: September 2026
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Journal Statistics Counters -->
<section class="py-4 bg-white border-bottom shadow-sm">
    <div class="container">
        <div class="row row-cols-2 row-cols-md-4 text-center g-3">
            <div class="col">
                <div class="h2 fw-bold text-primary mb-0 serif-font">{{ $stats['total_published'] }}</div>
                <div class="small text-uppercase fw-semibold text-muted">Published Articles</div>
            </div>
            <div class="col">
                <div class="h2 fw-bold text-success mb-0 serif-font">{{ $stats['total_downloads'] }}</div>
                <div class="small text-uppercase fw-semibold text-muted">Total Downloads</div>
            </div>
            <div class="col">
                <div class="h2 fw-bold text-info mb-0 serif-font">{{ $stats['total_volumes'] }}</div>
                <div class="small text-uppercase fw-semibold text-muted">Volumes Published</div>
            </div>
            <div class="col">
                <div class="h2 fw-bold text-warning mb-0 serif-font">14 Days</div>
                <div class="small text-uppercase fw-semibold text-muted">Avg. First Decision</div>
            </div>
        </div>
    </div>
</section>

<!-- Main Homepage Content Grid -->
<div class="container py-5">
    <div class="row g-4">

        <!-- Left Column: Articles & Current Issue -->
        <div class="col-lg-8">

            <!-- Latest Articles -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="serif-font fw-bold mb-0 text-dark">
                    <i class="bi bi-file-earmark-pdf-fill text-primary me-2"></i> Latest Published Articles
                </h3>
                <a href="{{ route('search') }}" class="btn btn-sm btn-outline-primary rounded-pill">View All Articles &rarr;</a>
            </div>

            <div class="d-flex flex-column gap-3 mb-5">
                @forelse($latestArticles as $article)
                    <div class="card journal-card p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge badge-category me-2">{{ $article->category }}</span>
                            <span class="small text-muted"><i class="bi bi-calendar3 me-1"></i> {{ $article->published_at?->format('M d, Y') ?? 'Recently Published' }}</span>
                        </div>
                        <h4 class="h5 fw-bold mb-2">
                            <a href="{{ route('article.detail', $article->id) }}" class="text-decoration-none text-dark hover-primary">
                                {{ $article->title }}
                            </a>
                        </h4>
                        <div class="small text-secondary mb-3">
                            <i class="bi bi-people me-1"></i> <strong>Authors:</strong> {{ $article->formatted_authors }}
                        </div>
                        <p class="small text-muted mb-3 line-clamp-2">
                            {{ Str::limit($article->abstract, 200) }}
                        </p>
                        <div class="d-flex flex-wrap align-items-center justify-content-between pt-2 border-top gap-2">
                            <div class="small text-muted">
                                <span class="me-3"><i class="bi bi-eye text-info me-1"></i> {{ $article->view_count }} Views</span>
                                <span><i class="bi bi-download text-success me-1"></i> {{ $article->download_count }} Downloads</span>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('article.detail', $article->id) }}" class="btn btn-sm btn-light border px-3">
                                    <i class="bi bi-file-text me-1"></i> Abstract
                                </a>
                                @if($article->manuscriptFile)
                                    <a href="{{ route('article.download', $article->id) }}" class="btn btn-sm btn-primary px-3">
                                        <i class="bi bi-file-earmark-pdf me-1"></i> Download PDF
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">No articles published yet.</div>
                @endforelse
            </div>

            <!-- Current Issue Showcase -->
            @if($currentIssue)
            <div class="bg-white p-4 rounded-4 border shadow-sm mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="h4 serif-font fw-bold mb-0 text-dark">
                        <i class="bi bi-journal-album text-success me-2"></i> Current Issue Showcase
                    </h3>
                    <span class="badge bg-success px-3 py-2 rounded-pill">Vol. {{ $currentIssue->volume?->volume_number }}, Issue {{ $currentIssue->issue_number }} ({{ $currentIssue->publication_year }})</span>
                </div>
                <p class="text-muted small mb-4">{{ $currentIssue->title ?? 'Latest Published Issue Table of Contents' }}</p>

                <div class="list-group list-group-flush">
                    @foreach($currentIssue->articles as $ciArticle)
                        <div class="list-group-item px-0 py-3">
                            <div class="fw-bold mb-1">
                                <a href="{{ route('article.detail', $ciArticle->id) }}" class="text-dark text-decoration-none">
                                    {{ $ciArticle->title }}
                                </a>
                            </div>
                            <div class="small text-muted mb-2">Authors: {{ $ciArticle->formatted_authors }}</div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="small text-primary"><i class="bi bi-file-earmark-text me-1"></i> Pages: {{ $ciArticle->start_page }}-{{ $ciArticle->end_page }}</span>
                                <a href="{{ route('article.detail', $ciArticle->id) }}" class="btn btn-sm btn-outline-secondary">Read Article &rarr;</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        <!-- Right Column: Sidebar Widgets -->
        <div class="col-lg-4">

            <!-- Journal Info Box -->
            <div class="bg-white p-4 rounded-4 border shadow-sm mb-4">
                <h5 class="serif-font fw-bold mb-3 border-bottom pb-2">Journal Quick Metadata</h5>
                <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                    <li class="d-flex justify-content-between">
                        <span class="text-muted">Journal Title:</span>
                        <strong class="text-dark text-end">IJASER</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted">Format:</span>
                        <strong class="text-dark">Online & Print</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted">DOI Prefix:</span>
                        <strong class="text-dark">10.5281/zenodo</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted">Peer Review:</span>
                        <strong class="text-dark">Double-Blind</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted">Frequency:</span>
                        <strong class="text-dark">Quarterly (4 Issues/Yr)</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted">License:</span>
                        <strong class="text-dark">CC BY 4.0 Open Access</strong>
                    </li>
                </ul>
            </div>

            <!-- Editorial Board Preview -->
            <div class="bg-white p-4 rounded-4 border shadow-sm mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                    <h5 class="serif-font fw-bold mb-0">Editorial Leadership</h5>
                    <a href="{{ route('editorial-board') }}" class="small text-primary">View All &rarr;</a>
                </div>
                <div class="d-flex flex-column gap-3">
                    @foreach($editorialMembers as $member)
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light rounded-circle text-primary fw-bold d-flex align-items-center justify-content-center border" style="width: 46px; height: 46px;">
                                <i class="bi bi-person-fill fs-4"></i>
                            </div>
                            <div>
                                <div class="fw-bold small text-dark">{{ $member->name }}</div>
                                <div class="small text-primary" style="font-size: 0.78rem;">{{ $member->designation }}</div>
                                <div class="small text-muted" style="font-size: 0.75rem;">{{ $member->university }}, {{ $member->country }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Latest News / Announcements -->
            <div class="bg-white p-4 rounded-4 border shadow-sm mb-4">
                <h5 class="serif-font fw-bold mb-3 border-bottom pb-2"><i class="bi bi-bell-fill text-warning me-2"></i> News & Announcements</h5>
                @php
                    $news = \App\Models\Announcement::where('is_active', true)->take(3)->get();
                @endphp
                <div class="d-flex flex-column gap-3">
                    @foreach($news as $item)
                        <div>
                            <span class="badge bg-light text-dark border mb-1" style="font-size: 0.7rem;">{{ strtoupper(str_replace('_', ' ', $item->type)) }}</span>
                            <div class="fw-bold small text-dark mb-1">{{ $item->title }}</div>
                            <p class="small text-muted mb-0">{{ Str::limit($item->content, 120) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
