@extends('layouts.admin')

@section('title', 'Volume & Issue Management')
@section('page_header', 'Volume & Issue Manager')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Volumes & Issues List</h5>
    <div class="d-flex gap-2">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newVolumeModal">
            <i class="bi bi-plus-lg me-1"></i> Create Volume
        </button>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newIssueModal">
            <i class="bi bi-plus-lg me-1"></i> Create Issue
        </button>
    </div>
</div>

<div class="row g-4">
    @foreach($volumes as $vol)
        <div class="col-lg-6">
            <div class="card bg-white border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h4 class="h5 fw-bold text-dark mb-0">Volume {{ $vol->volume_number }} (Year {{ $vol->year }})</h4>
                    <span class="badge bg-success">Active</span>
                </div>
                <p class="small text-muted mb-3">{{ $vol->description ?? 'Annual volume collection' }}</p>

                <h6 class="fw-bold small text-uppercase text-secondary mb-2">Associated Issues:</h6>
                <div class="list-group list-group-flush mb-3">
                    @forelse($vol->issues as $issue)
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <div>
                                <strong class="text-dark">Issue {{ $issue->issue_number }}</strong>
                                <span class="small text-muted ms-2">({{ $issue->publication_month }} {{ $issue->publication_year }})</span>
                                <div class="small text-secondary">{{ $issue->articles_count }} Assigned Articles</div>
                            </div>
                            <div>
                                @if($issue->is_published)
                                    <span class="badge bg-primary">Published</span>
                                @else
                                    <form action="{{ route('admin.issues.publish', $issue->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success fw-bold">Publish Issue</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="small text-muted py-2">No issues created under this volume yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Create Volume Modal -->
<div class="modal fade" id="newVolumeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.volumes.store') }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title fw-bold">Create New Volume</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Volume Number</label>
                        <input type="text" name="volume_number" class="form-control" placeholder="e.g. 13" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Year</label>
                        <input type="number" name="year" value="{{ date('Y') }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Volume Title / Description</label>
                        <textarea name="description" rows="2" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary fw-bold">Save Volume</button></div>
            </form>
        </div>
    </div>
</div>

<!-- Create Issue Modal -->
<div class="modal fade" id="newIssueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.issues.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header"><h5 class="modal-title fw-bold">Create New Issue</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Select Parent Volume</label>
                        <select name="volume_id" class="form-select" required>
                            @foreach($volumes as $v)
                                <option value="{{ $v->id }}">Volume {{ $v->volume_number }} ({{ $v->year }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Issue Number</label>
                        <input type="text" name="issue_number" class="form-control" placeholder="e.g. 1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Publication Month</label>
                        <input type="text" name="publication_month" class="form-control" placeholder="e.g. March" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Publication Year</label>
                        <input type="number" name="publication_year" value="{{ date('Y') }}" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-success fw-bold">Save Issue</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
