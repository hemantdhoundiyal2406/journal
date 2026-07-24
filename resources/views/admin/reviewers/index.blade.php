@extends('layouts.admin')

@section('title', 'Reviewer Database')
@section('page_header', 'Reviewer Database (Admin Reference Only)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Potential Reviewers</h5>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newReviewerModal">
        <i class="bi bi-person-plus-fill me-1"></i> Add Reviewer
    </button>
</div>

<div class="card bg-white border-0 shadow-sm rounded-4 p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Expertise</th>
                    <th>University & Country</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviewers as $rev)
                    <tr>
                        <td class="fw-bold text-dark">{{ $rev->name }}</td>
                        <td class="small">{{ $rev->email ?? 'N/A' }}</td>
                        <td><span class="badge bg-light text-primary border">{{ $rev->expertise }}</span></td>
                        <td class="small">{{ $rev->university }}, {{ $rev->country }}</td>
                        <td class="text-end">
                            <form action="{{ route('admin.reviewers.destroy', $rev->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove reviewer record?')"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-3 text-muted">No reviewers recorded in database.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="newReviewerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.reviewers.store') }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title fw-bold">Add Reviewer Record</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label small fw-semibold">Name *</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label small fw-semibold">Email</label><input type="email" name="email" class="form-control"></div>
                    <div class="mb-3"><label class="form-label small fw-semibold">Expertise Domains *</label><input type="text" name="expertise" class="form-control" placeholder="AI, Bioengineering, Quantum Computing..." required></div>
                    <div class="mb-3"><label class="form-label small fw-semibold">University *</label><input type="text" name="university" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label small fw-semibold">Country *</label><input type="text" name="country" class="form-control" required></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary fw-bold">Save Reviewer</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
