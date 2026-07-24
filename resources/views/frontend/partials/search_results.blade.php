<div class="d-flex justify-content-between align-items-center mb-3">
    <span class="text-muted small">Showing {{ $articles->total() }} matching articles</span>
</div>

<div class="d-flex flex-column gap-3 mb-4">
    @forelse($articles as $article)
        <div class="card journal-card p-4">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <span class="badge badge-category me-2">{{ $article->category }}</span>
                    <span class="badge bg-light text-dark border">{{ $article->manuscript_id }}</span>
                </div>
                <span class="small text-muted"><i class="bi bi-calendar3 me-1"></i> {{ $article->published_at?->format('M d, Y') }}</span>
            </div>
            <h4 class="h5 fw-bold mb-2">
                <a href="{{ route('article.detail', $article->id) }}" class="text-dark text-decoration-none hover-primary">
                    {{ $article->title }}
                </a>
            </h4>
            <div class="small text-secondary mb-2">Authors: {{ $article->formatted_authors }}</div>
            <p class="small text-muted mb-3 line-clamp-2">{{ Str::limit($article->abstract, 180) }}</p>
            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                <div class="small text-muted">
                    <span class="me-3"><i class="bi bi-eye text-info me-1"></i> {{ $article->view_count }}</span>
                    <span><i class="bi bi-download text-success me-1"></i> {{ $article->download_count }}</span>
                </div>
                <a href="{{ route('article.detail', $article->id) }}" class="btn btn-sm btn-outline-primary">Read Article &rarr;</a>
            </div>
        </div>
    @empty
        <div class="alert alert-warning text-center py-4">
            <i class="bi bi-exclamation-triangle fs-3 d-block mb-2 text-warning"></i>
            No articles match your current search criteria. Try adjusting keywords or category filters.
        </div>
    @endforelse
</div>

<div class="d-flex justify-content-center">
    {{ $articles->links() }}
</div>
