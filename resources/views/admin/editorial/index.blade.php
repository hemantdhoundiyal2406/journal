@extends('layouts.admin')

@section('title', 'Editorial Board Management')
@section('page_header', 'Editorial Board Directory')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Editorial Board Members</h5>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newMemberModal">
        <i class="bi bi-person-plus-fill me-1"></i> Add Board Member
    </button>
</div>

<div class="card bg-white border-0 shadow-sm rounded-4 p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>University & Country</th>
                    <th>ORCID / Scholar</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $m)
                    <tr>
                        <td class="fw-bold text-dark">{{ $m->name }}</td>
                        <td><span class="badge bg-primary">{{ $m->designation }}</span></td>
                        <td class="small">{{ $m->university }}, {{ $m->country }}</td>
                        <td class="small font-monospace">{{ $m->orcid ?? 'N/A' }}</td>
                        <td class="text-end">
                            <form action="{{ route('admin.editorial.destroy', $m->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove editorial member?')"><i class="bi bi-trash"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-3 text-muted">No board members added.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="newMemberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.editorial.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header"><h5 class="modal-title fw-bold">Add Editorial Member</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label small fw-semibold">Full Name *</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label small fw-semibold">Designation *</label><input type="text" name="designation" class="form-control" placeholder="Editor-in-Chief, Associate Editor..." required></div>
                    <div class="mb-3"><label class="form-label small fw-semibold">University / Institution *</label><input type="text" name="university" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label small fw-semibold">Country *</label><input type="text" name="country" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label small fw-semibold">ORCID iD</label><input type="text" name="orcid" class="form-control"></div>
                    <div class="mb-3"><label class="form-label small fw-semibold">Biography</label><textarea name="biography" rows="3" class="form-control"></textarea></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary fw-bold">Add Member</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
