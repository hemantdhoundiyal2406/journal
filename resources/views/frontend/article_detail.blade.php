@extends('layouts.frontend')

@section('title', $article->title . ' | IJASER Journal')
@section('meta_description', Str::limit(strip_tags($article->abstract), 160))
@section('meta_keywords', $article->keywords)

@section('google_scholar_meta')
    <!-- Google Scholar & HighWire Press Meta Tags -->
    <meta name="citation_title" content="{{ $article->title }}">
    @foreach($article->authors as $author)
        <meta name="citation_author" content="{{ $author->full_name }}">
        <meta name="citation_author_institution" content="{{ $author->institution }}">
    @endforeach
    <meta name="citation_publication_date" content="{{ $article->published_at?->format('Y/m/d') ?? date('Y/m/d') }}">
    <meta name="citation_journal_title" content="{{ \App\Models\JournalSetting::getByKey('journal_name') }}">
    <meta name="citation_volume" content="{{ $article->volume?->volume_number ?? '12' }}">
    <meta name="citation_issue" content="{{ $article->issue?->issue_number ?? '1' }}">
    <meta name="citation_firstpage" content="{{ $article->start_page ?? '1' }}">
    <meta name="citation_lastpage" content="{{ $article->end_page ?? '10' }}">
    @if($article->doi)
        <meta name="citation_doi" content="{{ $article->doi }}">
    @endif
    @if($article->manuscriptFile)
        <meta name="citation_pdf_url" content="{{ route('article.download', $article->id) }}">
    @endif

    <!-- Schema.org JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
      "{{ '@context' }}": "https://schema.org",
      "@type": "ScholarlyArticle",
      "headline": "{{ addslashes($article->title) }}",
      "abstract": "{{ addslashes(strip_tags($article->abstract)) }}",
      "datePublished": "{{ $article->published_at?->toIso8601String() }}",
      "keywords": "{{ $article->keywords }}",
      "author": [
        @foreach($article->authors as $index => $auth)
        {
          "@type": "Person",
          "name": "{{ $auth->full_name }}",
          "affiliation": {
            "@type": "Organization",
            "name": "{{ $auth->institution }}"
          }
        }@if(!$loop->last),@endif
        @endforeach
      ],
      "publisher": {
        "@type": "Organization",
        "name": "{{ \App\Models\JournalSetting::getByKey('publisher_name') }}"
      }
    }
    </script>
@endsection

@section('content')
<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2 small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('search') }}">Articles</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $article->manuscript_id }}</li>
            </ol>
        </nav>
        <span class="badge badge-category mb-2">{{ $article->category }}</span>
        <span class="badge bg-light text-dark border mb-2">{{ $article->article_type }}</span>

        <h1 class="h2 fw-bold text-dark serif-font mb-3">{{ $article->title }}</h1>

        <!-- Authors list with ORCID badges -->
        <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
            @foreach($article->authors as $author)
                <div class="bg-light p-2 px-3 rounded border">
                    <strong class="text-dark">{{ $author->full_name }}</strong>
                    @if($author->is_corresponding)
                        <span class="badge bg-primary ms-1" style="font-size: 0.65rem;">Corresponding</span>
                    @endif
                    <div class="small text-muted">{{ $author->institution }}, {{ $author->country }}</div>
                    @if($author->orcid)
                        <div class="small text-success" style="font-size: 0.75rem;">
                            <i class="bi bi-person-badge me-1"></i> ORCID: {{ $author->orcid }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="d-flex flex-wrap align-items-center gap-4 text-muted small border-top pt-3">
            <div><i class="bi bi-journal-bookmark me-1 text-primary"></i> <strong>Vol {{ $article->volume?->volume_number ?? '12' }}, Issue {{ $article->issue?->issue_number ?? '1' }}</strong> (pp. {{ $article->start_page ?? '1' }}-{{ $article->end_page ?? '10' }})</div>
            <div><i class="bi bi-calendar3 me-1 text-success"></i> Published: {{ $article->published_at?->format('F d, Y') ?? 'Recently' }}</div>
            <div><i class="bi bi-eye me-1 text-info"></i> {{ $article->view_count }} Views</div>
            <div><i class="bi bi-download me-1 text-warning"></i> {{ $article->download_count }} Downloads</div>
            @if($article->doi)
                <div><i class="bi bi-link-45deg me-1 text-danger"></i> DOI: <a href="https://doi.org/{{ $article->doi }}" target="_blank" class="text-decoration-none fw-bold">{{ $article->doi }}</a></div>
            @endif
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4">
        <!-- Abstract & Action Buttons -->
        <div class="col-lg-8">
            <div class="bg-white p-4 rounded-4 border shadow-sm mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="h4 serif-font fw-bold mb-0 text-dark">Abstract</h3>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#citationModal">
                            <i class="bi bi-quote me-1"></i> Cite Article
                        </button>
                        @if($article->manuscriptFile)
                            <a href="{{ route('article.download', $article->id) }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Download PDF
                            </a>
                        @endif
                    </div>
                </div>

                <p class="text-dark lh-lg fs-6" style="text-align: justify;">
                    {!! nl2br(e($article->abstract)) !!}
                </p>

                <hr class="my-4">

                <h5 class="fw-bold mb-2 text-dark serif-font">Keywords</h5>
                <div class="d-flex flex-wrap gap-2">
                    @foreach(explode(',', $article->keywords) as $kw)
                        <span class="badge bg-light text-secondary border px-3 py-2 fw-normal fs-7">{{ trim($kw) }}</span>
                    @endforeach
                </div>
            </div>

            <!-- Related Articles -->
            @if($relatedArticles->count() > 0)
            <div class="bg-white p-4 rounded-4 border shadow-sm">
                <h4 class="h5 serif-font fw-bold mb-3">Related Research Articles</h4>
                <div class="list-group list-group-flush">
                    @foreach($relatedArticles as $rel)
                        <a href="{{ route('article.detail', $rel->id) }}" class="list-group-item list-group-item-action px-0 py-3">
                            <div class="fw-bold text-dark mb-1">{{ $rel->title }}</div>
                            <div class="small text-muted">Authors: {{ $rel->formatted_authors }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Actions -->
        <div class="col-lg-4">
            <div class="bg-white p-4 rounded-4 border shadow-sm mb-4">
                <h5 class="serif-font fw-bold mb-3 border-bottom pb-2">Article Actions</h5>
                
                @if($article->manuscriptFile)
                    <a href="{{ route('article.download', $article->id) }}" class="btn btn-primary btn-lg w-100 fw-bold mb-3 shadow-sm">
                        <i class="bi bi-download me-2"></i> Download Full Text PDF
                    </a>
                @else
                    <div class="alert alert-warning small">Full text PDF pending publisher release.</div>
                @endif

                <button class="btn btn-outline-dark btn-md w-100 fw-bold mb-3" data-bs-toggle="modal" data-bs-target="#citationModal">
                    <i class="bi bi-bookmark-plus me-2"></i> Export Citations (APA / MLA / BibTeX)
                </button>

                <div class="border-top pt-3">
                    <span class="small text-uppercase fw-bold text-muted display-block mb-2">Share Article</span>
                    <div class="d-flex gap-2">
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-sm btn-outline-primary flex-fill"><i class="bi bi-twitter-x"></i> Share</a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-sm btn-outline-secondary flex-fill"><i class="bi bi-linkedin"></i> LinkedIn</a>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-4 border shadow-sm">
                <h5 class="serif-font fw-bold mb-3 border-bottom pb-2">License & Rights</h5>
                <p class="small text-muted mb-2">
                    <i class="bi bi-cc-circle-fill text-dark me-1"></i> Open Access article distributed under the terms of the <strong>Creative Commons Attribution 4.0 International License (CC BY 4.0)</strong>.
                </p>
                <p class="small text-muted mb-0">Authors retain copyright and full publishing rights without restrictions.</p>
            </div>
        </div>
    </div>
</div>

<!-- Citation Modal -->
<div class="modal fade" id="citationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold serif-font"><i class="bi bi-quote me-2"></i> Cite This Article</h5>
                <button type="button" class="btn-close" data-bs-dismiss="alert" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-pills mb-3" id="citationTabs" role="tablist">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#apaTab">APA</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#mlaTab">MLA</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#bibtexTab">BibTeX</button></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active p-3 bg-light rounded border" id="apaTab">
                        {{ $article->formatted_authors }} ({{ $article->published_at?->format('Y') ?? date('Y') }}). {{ $article->title }}. <em>{{ \App\Models\JournalSetting::getByKey('journal_name') }}</em>, {{ $article->volume?->volume_number ?? '12' }}({{ $article->issue?->issue_number ?? '1' }}), {{ $article->start_page ?? '1' }}-{{ $article->end_page ?? '10' }}. https://doi.org/{{ $article->doi ?? '10.5281/zenodo' }}
                    </div>
                    <div class="tab-pane fade p-3 bg-light rounded border" id="mlaTab">
                        {{ $article->formatted_authors }}. "{{ $article->title }}." <em>{{ \App\Models\JournalSetting::getByKey('journal_name') }}</em>, vol. {{ $article->volume?->volume_number ?? '12' }}, no. {{ $article->issue?->issue_number ?? '1' }}, {{ $article->published_at?->format('Y') ?? date('Y') }}, pp. {{ $article->start_page ?? '1' }}-{{ $article->end_page ?? '10' }}.
                    </div>
                    <div class="tab-pane fade p-3 bg-light rounded border font-monospace small" id="bibtexTab">
                        {{ '@article' }}{ijaser_{{ $article->id }},<br>
                        &nbsp;&nbsp;title={{{ '{' }}}{{ $article->title }}{{ '}' }}},<br>
                        &nbsp;&nbsp;author={{{ '{' }}}{{ $article->formatted_authors }}{{ '}' }}},<br>
                        &nbsp;&nbsp;journal={{{ '{' }}}{{ \App\Models\JournalSetting::getByKey('journal_name') }}{{ '}' }}},<br>
                        &nbsp;&nbsp;volume={{{ '{' }}}{{ $article->volume?->volume_number ?? '12' }}{{ '}' }}},<br>
                        &nbsp;&nbsp;number={{{ '{' }}}{{ $article->issue?->issue_number ?? '1' }}{{ '}' }}},<br>
                        &nbsp;&nbsp;pages={{{ '{' }}}{{ $article->start_page ?? '1' }}-{{ $article->end_page ?? '10' }}{{ '}' }}},<br>
                        &nbsp;&nbsp;year={{{ '{' }}}{{ $article->published_at?->format('Y') ?? date('Y') }}{{ '}' }}}<br>
                        }
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
