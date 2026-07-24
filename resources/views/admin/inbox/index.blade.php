@extends('layouts.admin')

@section('title', 'Submission Inbox')
@section('page_header', 'New Manuscript Inbox & Letter Generator')

@section('content')
<div class="card bg-white border-0 shadow-sm rounded-4 p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th>Manuscript ID</th>
                    <th>Title & Category</th>
                    <th>Authors</th>
                    <th>Submitted Date</th>
                    <th class="text-end">Download Official PDF Letters</th>
                </tr>
            </thead>
            <tbody>
                @forelse($newSubmissions as $submission)
                    <tr>
                        <td><strong class="font-monospace text-primary fs-6">{{ $submission->manuscript_id }}</strong></td>
                        <td>
                            <div class="fw-bold text-dark text-truncate" style="max-width: 320px;">{{ $submission->title }}</div>
                            <span class="badge bg-light text-secondary border">{{ $submission->category }}</span>
                        </td>
                        <td class="small">{{ $submission->formatted_authors }}</td>
                        <td class="small text-muted">{{ $submission->created_at->format('M d, Y h:i A') }}</td>
                        <td class="text-end">
                            <!-- Direct PDF Letter Download Dropdown -->
                            <div class="dropdown d-inline-block">
                                <button class="btn btn-sm btn-primary dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-file-pdf-fill me-1"></i> Download PDF Letters
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                    <li>
                                        <a class="dropdown-menu-item text-success dropdown-item py-2" href="{{ route('admin.articles.letter', ['id' => $submission->id, 'type' => 'acceptance']) }}">
                                            <i class="bi bi-file-earmark-check me-2"></i> Acceptance Letter
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger py-2" href="{{ route('admin.articles.letter', ['id' => $submission->id, 'type' => 'rejection']) }}">
                                            <i class="bi bi-file-earmark-x me-2"></i> Rejection Letter
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-warning py-2" href="{{ route('admin.articles.letter', ['id' => $submission->id, 'type' => 'revision']) }}">
                                            <i class="bi bi-file-earmark-arrow-up me-2"></i> Revision Request
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-primary py-2" href="{{ route('admin.articles.letter', ['id' => $submission->id, 'type' => 'publication']) }}">
                                            <i class="bi bi-file-earmark-medical me-2"></i> Publication Certificate Letter
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-info py-2" href="{{ route('admin.articles.letter', ['id' => $submission->id, 'type' => 'copyright']) }}">
                                            <i class="bi bi-shield-check me-2"></i> Copyright Agreement
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-dark fw-bold py-2" href="{{ route('admin.articles.certificate', $submission->id) }}">
                                            <i class="bi bi-award-fill text-warning me-2"></i> QR Author Certificate
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <a href="{{ route('admin.articles.show', $submission->id) }}" class="btn btn-sm btn-outline-dark ms-1">
                                Full Screen
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No new submissions in inbox.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $newSubmissions->links() }}
    </div>
</div>
@endsection
