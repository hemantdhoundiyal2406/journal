@extends('layouts.admin')

@section('title', 'Published Articles & DOI Management')
@section('page_header', 'Published Articles Repository & DOI Indexing')

@section('content')
<div class="card bg-white border-0 shadow-sm rounded-4 p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th>Manuscript ID</th>
                    <th>Title</th>
                    <th>Assigned DOI</th>
                    <th>Pages</th>
                    <th>Views / Downloads</th>
                    <th class="text-end">Manage DOI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                    <tr>
                        <td><strong class="font-monospace text-primary">{{ $article->manuscript_id }}</strong></td>
                        <td>
                            <div class="fw-bold text-dark text-truncate" style="max-width: 280px;">{{ $article->title }}</div>
                            <small class="text-muted">Published: {{ $article->published_at?->format('M d, Y') }}</small>
                        </td>
                        <td><span class="font-monospace fw-bold text-success">{{ $article->doi ?? 'Pending DOI' }}</span></td>
                        <td class="small">pp. {{ $article->start_page ?? '1' }}-{{ $article->end_page ?? '10' }}</td>
                        <td class="small">
                            <span class="me-2"><i class="bi bi-eye text-info me-1"></i> {{ $article->view_count }}</span>
                            <span><i class="bi bi-download text-warning me-1"></i> {{ $article->download_count }}</span>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#doiModal{{ $article->id }}">
                                Update DOI
                            </button>
                            <a href="{{ route('admin.articles.show', $article->id) }}" class="btn btn-sm btn-dark ms-1">
                                Screen
                            </a>
                        </td>
                    </tr>

                    <!-- DOI Edit Modal -->
                    <div class="modal fade" id="doiModal{{ $article->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form action="{{ route('admin.published.update-doi', $article->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-header"><h5 class="modal-title fw-bold">Update DOI: {{ $article->manuscript_id }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                    <div class="modal-body text-start">
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">DOI String</label>
                                            <input type="text" name="doi" value="{{ $article->doi }}" class="form-control font-monospace" placeholder="10.5281/zenodo.1026001" required>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">Start Page</label>
                                                <input type="text" name="start_page" value="{{ $article->start_page }}" class="form-control">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-semibold">End Page</label>
                                                <input type="text" name="end_page" value="{{ $article->end_page }}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer"><button type="submit" class="btn btn-success fw-bold">Save DOI</button></div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">No published articles found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $articles->links() }}
    </div>
</div>
@endsection
