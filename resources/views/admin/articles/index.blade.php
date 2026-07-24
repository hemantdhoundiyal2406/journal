@extends('layouts.admin')

@section('title', 'Article Lifecycle Management')
@section('page_header', 'All Submissions & Articles')

@section('content')
<div class="card bg-white border-0 shadow-sm rounded-4 p-4 mb-4">
    <!-- Filter Header Bar -->
    <form action="{{ route('admin.articles.index') }}" method="GET" class="row g-3 align-items-center mb-3">
        <div class="col-md-4">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by ID, Title, Author, DOI...">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="Submitted" {{ request('status') === 'Submitted' ? 'selected' : '' }}>Submitted</option>
                <option value="Screening" {{ request('status') === 'Screening' ? 'selected' : '' }}>Screening</option>
                <option value="Under Review" {{ request('status') === 'Under Review' ? 'selected' : '' }}>Under Review</option>
                <option value="Revision Required" {{ request('status') === 'Revision Required' ? 'selected' : '' }}>Revision Required</option>
                <option value="Revised Received" {{ request('status') === 'Revised Received' ? 'selected' : '' }}>Revised Received</option>
                <option value="Accepted" {{ request('status') === 'Accepted' ? 'selected' : '' }}>Accepted</option>
                <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="Published" {{ request('status') === 'Published' ? 'selected' : '' }}>Published</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                <option value="Computer Science & Security">Computer Science & Security</option>
                <option value="Engineering & Technology">Engineering & Technology</option>
                <option value="Medical & Life Sciences">Medical & Life Sciences</option>
                <option value="Environmental Sciences">Environmental Sciences</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100 fw-bold">Filter</button>
        </div>
    </form>

    <!-- Articles Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th>Manuscript ID</th>
                    <th>Title</th>
                    <th>Authors</th>
                    <th>Status</th>
                    <th>Volume / Issue</th>
                    <th>DOI</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                    <tr>
                        <td>
                            <strong class="font-monospace text-primary">{{ $article->manuscript_id }}</strong><br>
                            <small class="text-muted">{{ $article->created_at->format('M d, Y') }}</small>
                        </td>
                        <td>
                            <div class="fw-bold text-dark text-truncate" style="max-width: 300px;" title="{{ $article->title }}">
                                {{ $article->title }}
                            </div>
                            <span class="badge bg-light text-secondary border">{{ $article->category }}</span>
                        </td>
                        <td class="small">{{ $article->formatted_authors }}</td>
                        <td>
                            @php
                                $badgeClass = match($article->status) {
                                    'Submitted' => 'bg-danger',
                                    'Under Review' => 'bg-warning text-dark',
                                    'Accepted' => 'bg-success',
                                    'Published' => 'bg-primary',
                                    'Rejected' => 'bg-dark',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $article->status }}</span>
                        </td>
                        <td class="small">
                            @if($article->issue)
                                Vol {{ $article->volume?->volume_number }}, Iss {{ $article->issue?->issue_number }}
                            @else
                                <span class="text-muted">Unassigned</span>
                            @endif
                        </td>
                        <td class="small font-monospace">{{ $article->doi ?? 'None' }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.articles.show', $article->id) }}" class="btn btn-sm btn-primary fw-bold">
                                <i class="bi bi-sliders me-1"></i> Dedicated Screen
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No articles found matching filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $articles->links() }}
    </div>
</div>
@endsection
