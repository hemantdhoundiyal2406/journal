@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page_header', 'Dashboard Overview')

@section('content')
<!-- Stat Cards Row -->
<div class="row row-cols-1 row-cols-md-4 g-3 mb-4">
    <div class="col">
        <div class="card card-stat bg-white p-3 border">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-secondary small fw-bold text-uppercase">Total Submissions</div>
                    <div class="h2 fw-bold text-dark mb-0">{{ $stats['total_articles'] }}</div>
                </div>
                <div class="bg-primary text-white p-3 rounded-circle fs-4">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card card-stat bg-white p-3 border">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-secondary small fw-bold text-uppercase">New Submissions</div>
                    <div class="h2 fw-bold text-danger mb-0">{{ $stats['new_submissions'] }}</div>
                </div>
                <div class="bg-danger text-white p-3 rounded-circle fs-4">
                    <i class="bi bi-inbox-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card card-stat bg-white p-3 border">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-secondary small fw-bold text-uppercase">Under Peer Review</div>
                    <div class="h2 fw-bold text-warning mb-0">{{ $stats['under_review'] }}</div>
                </div>
                <div class="bg-warning text-dark p-3 rounded-circle fs-4">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card card-stat bg-white p-3 border">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-secondary small fw-bold text-uppercase">Published Articles</div>
                    <div class="h2 fw-bold text-success mb-0">{{ $stats['published'] }}</div>
                </div>
                <div class="bg-success text-white p-3 rounded-circle fs-4">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Submissions Feed -->
    <div class="col-lg-8">
        <div class="card bg-white border-0 shadow-sm rounded-4 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Latest Submissions</h5>
                <a href="{{ route('admin.inbox.index') }}" class="btn btn-sm btn-primary">Go to Inbox &rarr;</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-uppercase">
                        <tr>
                            <th>ID</th>
                            <th>Title & Category</th>
                            <th>Authors</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestSubmissions as $item)
                            <tr>
                                <td><strong class="font-monospace text-primary">{{ $item->manuscript_id }}</strong></td>
                                <td>
                                    <div class="fw-bold text-dark text-truncate" style="max-width: 280px;">{{ $item->title }}</div>
                                    <span class="badge bg-light text-secondary border">{{ $item->category }}</span>
                                </td>
                                <td class="small">{{ $item->formatted_authors }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $item->status }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.articles.show', $item->id) }}" class="btn btn-sm btn-outline-dark">
                                        Manage
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-3 text-muted">No submissions found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Activity Timeline -->
    <div class="col-lg-4">
        <div class="card bg-white border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold mb-3">Recent Article Log</h5>
            <div class="d-flex flex-column gap-3">
                @foreach($recentActivities as $act)
                    <div class="border-start border-3 border-primary ps-3">
                        <div class="small fw-bold text-dark">{{ $act->article?->manuscript_id ?? 'Article' }} &rarr; {{ $act->status_to }}</div>
                        <div class="small text-muted">{{ $act->comment }}</div>
                        <div class="small text-secondary" style="font-size: 0.75rem;">{{ $act->created_at->diffForHumans() }} by {{ $act->created_by }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
