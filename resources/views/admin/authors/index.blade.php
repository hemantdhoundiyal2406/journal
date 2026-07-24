@extends('layouts.admin')

@section('title', 'Author Database')
@section('page_header', 'Centralized Author Repository')

@section('content')
<div class="card bg-white border-0 shadow-sm rounded-4 p-4">
    <form action="{{ route('admin.authors.index') }}" method="GET" class="row g-3 align-items-center mb-3">
        <div class="col-md-6">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by Author Name, Email, Institution, Country, ORCID...">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100 fw-bold">Search Authors</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Institution</th>
                    <th>Country</th>
                    <th>ORCID</th>
                    <th>Total Articles</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($authors as $author)
                    <tr>
                        <td class="fw-bold text-dark">{{ $author->full_name }}</td>
                        <td class="small">{{ $author->email }}</td>
                        <td class="small">{{ $author->institution }}</td>
                        <td class="small">{{ $author->country }}</td>
                        <td class="small font-monospace">{{ $author->orcid ?? 'N/A' }}</td>
                        <td><span class="badge bg-primary rounded-pill">{{ $author->total_articles_count }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('admin.authors.show', $author->id) }}" class="btn btn-sm btn-outline-dark">
                                View Articles
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No authors found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $authors->links() }}
    </div>
</div>
@endsection
