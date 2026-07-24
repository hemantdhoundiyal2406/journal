@extends('layouts.admin')

@section('title', 'Announcements & News')
@section('page_header', 'Announcements & Pop-up Notices')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Active Announcements</h5>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newAnnModal">
        <i class="bi bi-megaphone-fill me-1"></i> Create Announcement
    </button>
</div>

<div class="card bg-white border-0 shadow-sm rounded-4 p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th>Type</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($announcements as $ann)
                    <tr>
                        <td><span class="badge bg-info text-dark">{{ strtoupper($ann->type) }}</span></td>
                        <td class="fw-bold text-dark">{{ $ann->title }}</td>
                        <td class="small text-muted">{{ Str::limit($ann->content, 80) }}</td>
                        <td>
                            @if($ann->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Disabled</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <form action="{{ route('admin.announcements.toggle', $ann->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-warning">Toggle Status</button>
                            </form>
                            <form action="{{ route('admin.announcements.destroy', $ann->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete announcement?')"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-3 text-muted">No announcements created.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="newAnnModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.announcements.store') }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title fw-bold">New Announcement</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Display Target Type</label>
                        <select name="type" class="form-select">
                            <option value="top_bar">Header Top Banner Bar</option>
                            <option value="popup_notice">Pop-up Modal Notice</option>
                            <option value="call_for_papers">Call for Papers Widget</option>
                            <option value="latest_news">Latest Journal News</option>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label small fw-semibold">Title *</label><input type="text" name="title" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label small fw-semibold">Content *</label><textarea name="content" rows="3" class="form-control" required></textarea></div>
                    <div class="mb-3"><label class="form-label small fw-semibold">Target Action Link (Optional)</label><input type="url" name="link" class="form-control" placeholder="https://..."></div>
                    <div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" id="ann_active" value="1" checked><label class="form-check-label small fw-semibold" for="ann_active">Active Immediately</label></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary fw-bold">Publish Announcement</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
