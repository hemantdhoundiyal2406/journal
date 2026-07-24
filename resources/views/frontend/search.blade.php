@extends('layouts.frontend')

@section('title', 'Search Research Articles | IJASER Journal')

@section('content')
<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <h1 class="h2 fw-bold text-dark serif-font mb-1"><i class="bi bi-search text-primary me-2"></i> Search Articles & Archives</h1>
        <p class="text-muted mb-0">Search published papers across all volumes by title, author name, institution, DOI, or keywords.</p>
    </div>
</div>

<div class="container pb-5">
    <!-- Filter Controls Bar -->
    <div class="bg-white p-4 rounded-4 border shadow-sm mb-4">
        <form id="searchForm" action="{{ route('search') }}" method="GET" class="row g-3">
            <div class="col-lg-5">
                <label class="form-label fw-semibold small">Search Query</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" id="searchInput" class="form-control" value="{{ $query }}" placeholder="Manuscript ID, Title, Author, DOI...">
                </div>
            </div>

            <div class="col-lg-3">
                <label class="form-label fw-semibold small">Category</label>
                <select name="category" id="categoryFilter" class="form-select">
                    <option value="">All Categories</option>
                    <option value="Computer Science & Security" {{ $category === 'Computer Science & Security' ? 'selected' : '' }}>Computer Science & Security</option>
                    <option value="Engineering & Technology" {{ $category === 'Engineering & Technology' ? 'selected' : '' }}>Engineering & Technology</option>
                    <option value="Medical & Life Sciences" {{ $category === 'Medical & Life Sciences' ? 'selected' : '' }}>Medical & Life Sciences</option>
                    <option value="Environmental Sciences" {{ $category === 'Environmental Sciences' ? 'selected' : '' }}>Environmental Sciences</option>
                </select>
            </div>

            <div class="col-lg-2">
                <label class="form-label fw-semibold small">Sort By</label>
                <select name="sort" id="sortFilter" class="form-select">
                    <option value="latest" {{ $sortBy === 'latest' ? 'selected' : '' }}>Latest Published</option>
                    <option value="views" {{ $sortBy === 'views' ? 'selected' : '' }}>Most Viewed</option>
                    <option value="downloads" {{ $sortBy === 'downloads' ? 'selected' : '' }}>Most Downloaded</option>
                </select>
            </div>

            <div class="col-lg-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100 fw-bold">Filter Results</button>
            </div>
        </form>
    </div>

    <!-- Search Results Container -->
    <div id="resultsContainer">
        @include('frontend.partials.search_results', ['articles' => $articles])
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let timer;
        $('#searchInput, #categoryFilter, #sortFilter').on('input change', function() {
            clearTimeout(timer);
            timer = setTimeout(function() {
                let formData = $('#searchForm').serialize();
                $.get("{{ route('search') }}", formData, function(data) {
                    $('#resultsContainer').html(data.html);
                });
            }, 300);
        });
    });
</script>
@endsection
