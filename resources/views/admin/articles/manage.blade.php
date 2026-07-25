@extends('layouts.admin')

@section('title', "Manage {$article->manuscript_id}")
@section('page_header', "Single Article Screen: {$article->manuscript_id}")

@section('content')
<!-- Header Card -->
<div class="card bg-white border-0 shadow-sm rounded-4 p-4 mb-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <span class="badge bg-primary font-monospace fs-6 px-3 py-2 me-2">{{ $article->manuscript_id }}</span>
            <span class="badge bg-light text-dark border me-2">{{ $article->category }}</span>
            <span class="badge bg-secondary me-2">{{ $article->article_type }}</span>
            <h3 class="fw-bold text-dark mt-2 mb-1">{{ $article->title }}</h3>
            <div class="text-secondary small">Submitted on {{ $article->created_at->format('F d, Y \a\t h:i A') }}</div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="fs-6 fw-bold me-2">Current Status:</span>
            <span class="badge bg-success fs-6 px-3 py-2">{{ $article->status }}</span>
        </div>
    </div>
</div>

<!-- Dedicated 9-Tab Navigation Screen -->
<div class="card bg-white border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white border-bottom p-0">
        <div class="article-tabs-wrapper">
            <ul class="nav nav-tabs article-tabs px-3 border-0" id="articleTabs" role="tablist">
                <li class="nav-item"><a class="nav-link {{ $activeTab === 'overview' ? 'active' : '' }}" href="?tab=overview"><i class="bi bi-info-circle me-1"></i> 1. Overview</a></li>
                <li class="nav-item"><a class="nav-link {{ $activeTab === 'authors' ? 'active' : '' }}" href="?tab=authors"><i class="bi bi-people me-1"></i> 2. Authors</a></li>
                <li class="nav-item"><a class="nav-link {{ $activeTab === 'files' ? 'active' : '' }}" href="?tab=files"><i class="bi bi-folder-symlink me-1"></i> 3. Files</a></li>
                <li class="nav-item"><a class="nav-link {{ $activeTab === 'notes' ? 'active' : '' }}" href="?tab=notes"><i class="bi bi-journal-text me-1"></i> 4. Notes</a></li>
                <li class="nav-item"><a class="nav-link {{ $activeTab === 'timeline' ? 'active' : '' }}" href="?tab=timeline"><i class="bi bi-clock-history me-1"></i> 5. Timeline</a></li>
                <li class="nav-item"><a class="nav-link {{ $activeTab === 'letters' ? 'active' : '' }}" href="?tab=letters"><i class="bi bi-file-pdf me-1"></i> 6. PDF Letters</a></li>
                <li class="nav-item"><a class="nav-link {{ $activeTab === 'emails' ? 'active' : '' }}" href="?tab=emails"><i class="bi bi-envelope me-1"></i> 7. Emails</a></li>
                <li class="nav-item"><a class="nav-link {{ $activeTab === 'publication' ? 'active' : '' }}" href="?tab=publication"><i class="bi bi-journal-bookmark me-1"></i> 8. Publication</a></li>
                <li class="nav-item"><a class="nav-link {{ $activeTab === 'doi' ? 'active' : '' }}" href="?tab=doi"><i class="bi bi-link-45deg me-1"></i> 9. DOI</a></li>
            </ul>
        </div>
    </div>

    <div class="card-body p-4">

        <!-- TAB 1: OVERVIEW -->
        @if($activeTab === 'overview')
            <div class="row g-4">
                <div class="col-lg-8">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Abstract</h5>
                    <p class="lh-lg text-dark">{{ $article->abstract }}</p>

                    <h5 class="fw-bold mb-2 border-bottom pb-2 mt-4">Keywords</h5>
                    <p class="text-secondary">{{ $article->keywords }}</p>
                </div>

                <div class="col-lg-4">
                    <div class="bg-light p-3 rounded-3 border">
                        <h6 class="fw-bold mb-3 border-bottom pb-2">Change Lifecycle Status</h6>
                        <form action="{{ route('admin.articles.update-status', $article->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">New Status</label>
                                <select name="status" class="form-select">
                                    @foreach(['Submitted', 'Screening', 'Under Review', 'Revision Required', 'Revised Received', 'Accepted', 'Rejected', 'Published'] as $st)
                                        <option value="{{ $st }}" {{ $article->status === $st ? 'selected' : '' }}>{{ $st }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Audit Comment</label>
                                <textarea name="comment" rows="3" class="form-control form-control-sm" placeholder="Reason for status change..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">Update Status</button>
                        </form>
                    </div>
                </div>
            </div>

        <!-- TAB 2: AUTHORS -->
        @elseif($activeTab === 'authors')
            <h5 class="fw-bold mb-3">Author List & Affiliations</h5>
            <form action="{{ route('admin.articles.update-authors', $article->id) }}" method="POST">
                @csrf
                <div class="table-responsive mb-3">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light small text-uppercase">
                            <tr>
                                <th>Name</th>
                                <th>Email & Mobile</th>
                                <th>Institution & Country</th>
                                <th>ORCID</th>
                                <th>Corresponding</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($article->authors as $idx => $author)
                                <tr>
                                    <td>
                                        <input type="text" name="authors[{{ $idx }}][first_name]" value="{{ $author->first_name }}" class="form-control form-control-sm mb-1" placeholder="First Name">
                                        <input type="text" name="authors[{{ $idx }}][last_name]" value="{{ $author->last_name }}" class="form-control form-control-sm" placeholder="Last Name">
                                    </td>
                                    <td>
                                        <input type="email" name="authors[{{ $idx }}][email]" value="{{ $author->email }}" class="form-control form-control-sm mb-1" placeholder="Email">
                                        <input type="text" name="authors[{{ $idx }}][mobile]" value="{{ $author->mobile }}" class="form-control form-control-sm" placeholder="Mobile">
                                    </td>
                                    <td>
                                        <input type="text" name="authors[{{ $idx }}][institution]" value="{{ $author->institution }}" class="form-control form-control-sm mb-1" placeholder="Institution">
                                        <input type="text" name="authors[{{ $idx }}][country]" value="{{ $author->country }}" class="form-control form-control-sm" placeholder="Country">
                                    </td>
                                    <td>
                                        <input type="text" name="authors[{{ $idx }}][orcid]" value="{{ $author->orcid }}" class="form-control form-control-sm" placeholder="ORCID">
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="corresponding_index" value="{{ $idx }}" {{ $author->is_corresponding ? 'checked' : '' }}>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-success btn-sm fw-bold px-4">Save Author Changes</button>
            </form>

        <!-- TAB 3: FILES -->
        @elseif($activeTab === 'files')
            <div class="row g-4">
                <div class="col-lg-8">
                    <h5 class="fw-bold mb-3">Uploaded Article Files</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light small text-uppercase">
                                <tr>
                                    <th>File Type</th>
                                    <th>Original Name</th>
                                    <th>Size</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($article->files as $file)
                                    <tr>
                                        <td><span class="badge bg-info text-dark">{{ strtoupper($file->file_type) }}</span></td>
                                        <td>{{ $file->original_name }}</td>
                                        <td class="small">{{ round($file->file_size / 1024, 2) }} KB</td>
                                        <td>
                                            <a href="{{ route('admin.articles.download-file', ['id' => $article->id, 'fileId' => $file->id]) }}" class="btn btn-sm btn-outline-primary me-1">
                                                <i class="bi bi-download"></i> Download
                                            </a>
                                            <form action="{{ route('admin.articles.delete-file', ['id' => $article->id, 'fileId' => $file->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete file?')"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-3 text-muted">No files uploaded.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="bg-light p-3 rounded-3 border">
                        <h6 class="fw-bold mb-3">Upload Additional File</h6>
                        <form action="{{ route('admin.articles.upload-file', $article->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">File Category</label>
                                <select name="file_type" class="form-select">
                                    <option value="manuscript">Manuscript PDF/DOCX</option>
                                    <option value="supplementary">Supplementary File</option>
                                    <option value="cover_letter">Cover Letter</option>
                                    <option value="image">High-Res Image</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Select File</label>
                                <input type="file" name="file" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">Upload File</button>
                        </form>
                    </div>
                </div>
            </div>

        <!-- TAB 4: NOTES -->
        @elseif($activeTab === 'notes')
            <h5 class="fw-bold mb-3">Internal Editorial Notes</h5>
            <form action="{{ route('admin.articles.update-notes', $article->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <textarea name="admin_notes" rows="8" class="form-control" placeholder="Write confidential internal reviewer comments, preliminary screening observations, or admin log notes here...">{{ $article->admin_notes }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary fw-bold px-4">Save Internal Notes</button>
            </form>

        <!-- TAB 5: TIMELINE -->
        @elseif($activeTab === 'timeline')
            <h5 class="fw-bold mb-3">Status Transition Timeline</h5>
            <div class="timeline position-relative ps-4 border-start border-2 border-primary">
                @foreach($article->timelines as $time)
                    <div class="mb-4 position-relative">
                        <span class="position-absolute top-0 start-0 translate-middle bg-primary rounded-circle" style="width: 12px; height: 12px; margin-left: -25px;"></span>
                        <div class="fw-bold text-dark">{{ $time->status_from ?? 'Creation' }} &rarr; {{ $time->status_to }}</div>
                        <div class="text-secondary small">{{ $time->comment }}</div>
                        <div class="text-muted small" style="font-size: 0.75rem;">{{ $time->created_at->format('M d, Y h:i A') }} by {{ $time->created_by }}</div>
                    </div>
                @endforeach
            </div>

        <!-- TAB 6: LETTERS -->
        @elseif($activeTab === 'letters')
            <h5 class="fw-bold mb-3">Generate & Download Official PDF Letters</h5>
            <p class="text-secondary small mb-4">Click below to automatically generate computer-signed PDF letters formatted with journal masthead.</p>

            <div class="row row-cols-1 row-cols-md-3 g-3">
                <div class="col">
                    <div class="border p-3 rounded-3 bg-light text-center">
                        <i class="bi bi-file-earmark-check fs-1 text-success d-block mb-2"></i>
                        <h6 class="fw-bold">Acceptance Letter</h6>
                        <a href="{{ route('admin.articles.letter', ['id' => $article->id, 'type' => 'acceptance']) }}" class="btn btn-sm btn-success w-100 fw-bold">Download PDF &rarr;</a>
                    </div>
                </div>
                <div class="col">
                    <div class="border p-3 rounded-3 bg-light text-center">
                        <i class="bi bi-file-earmark-x fs-1 text-danger d-block mb-2"></i>
                        <h6 class="fw-bold">Rejection Letter</h6>
                        <a href="{{ route('admin.articles.letter', ['id' => $article->id, 'type' => 'rejection']) }}" class="btn btn-sm btn-danger w-100 fw-bold">Download PDF &rarr;</a>
                    </div>
                </div>
                <div class="col">
                    <div class="border p-3 rounded-3 bg-light text-center">
                        <i class="bi bi-file-earmark-arrow-up fs-1 text-warning d-block mb-2"></i>
                        <h6 class="fw-bold">Revision Request</h6>
                        <a href="{{ route('admin.articles.letter', ['id' => $article->id, 'type' => 'revision']) }}" class="btn btn-sm btn-warning text-dark w-100 fw-bold">Download PDF &rarr;</a>
                    </div>
                </div>
                <div class="col">
                    <div class="border p-3 rounded-3 bg-light text-center">
                        <i class="bi bi-file-earmark-medical fs-1 text-primary d-block mb-2"></i>
                        <h6 class="fw-bold">Publication Confirmation</h6>
                        <a href="{{ route('admin.articles.letter', ['id' => $article->id, 'type' => 'publication']) }}" class="btn btn-sm btn-primary w-100 fw-bold">Download PDF &rarr;</a>
                    </div>
                </div>
                <div class="col">
                    <div class="border p-3 rounded-3 bg-light text-center">
                        <i class="bi bi-shield-check fs-1 text-info d-block mb-2"></i>
                        <h6 class="fw-bold">Copyright Agreement</h6>
                        <a href="{{ route('admin.articles.letter', ['id' => $article->id, 'type' => 'copyright']) }}" class="btn btn-sm btn-info text-white w-100 fw-bold">Download PDF &rarr;</a>
                    </div>
                </div>
                <div class="col">
                    <div class="border p-3 rounded-3 bg-light text-center">
                        <i class="bi bi-award fs-1 text-warning d-block mb-2"></i>
                        <h6 class="fw-bold">QR Author Certificate</h6>
                        <a href="{{ route('admin.articles.certificate', $article->id) }}" class="btn btn-sm btn-dark w-100 fw-bold">Download Certificate &rarr;</a>
                    </div>
                </div>
            </div>

        <!-- TAB 7: EMAILS -->
        @elseif($activeTab === 'emails')
            <h5 class="fw-bold mb-3">Send Templated Email to Authors</h5>
            <form action="{{ route('admin.articles.send-email', $article->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Subject Line</label>
                    <input type="text" name="subject" class="form-control" value="Update regarding Manuscript {{ $article->manuscript_id }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Message Body</label>
                    <textarea name="message" rows="6" class="form-control" required>Dear {{ $article->correspondingAuthor?->full_name ?? 'Author' }},

We are writing regarding your submitted manuscript "{{ $article->title }}" (ID: {{ $article->manuscript_id }})...</textarea>
                </div>
                <button type="submit" class="btn btn-primary fw-bold px-4">Send Email Notification</button>
            </form>

        <!-- TAB 8: PUBLICATION -->
        @elseif($activeTab === 'publication')
            <h5 class="fw-bold mb-3">Assign Volume, Issue & Page Range</h5>
            <form action="{{ route('admin.articles.update-publication', $article->id) }}" method="POST">
                @csrf
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Volume</label>
                        <select name="volume_id" class="form-select">
                            <option value="">Select Volume</option>
                            @foreach($volumes as $vol)
                                <option value="{{ $vol->id }}" {{ $article->volume_id === $vol->id ? 'selected' : '' }}>Volume {{ $vol->volume_number }} ({{ $vol->year }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Issue</label>
                        <select name="issue_id" class="form-select">
                            <option value="">Select Issue</option>
                            @foreach($issues as $iss)
                                <option value="{{ $iss->id }}" {{ $article->issue_id === $iss->id ? 'selected' : '' }}>Issue {{ $iss->issue_number }} ({{ $iss->publication_year }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Start Page</label>
                        <input type="text" name="start_page" value="{{ $article->start_page }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">End Page</label>
                        <input type="text" name="end_page" value="{{ $article->end_page }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Publication Date</label>
                        <input type="date" name="published_at" value="{{ $article->published_at?->format('Y-m-d') }}" class="form-control">
                    </div>
                </div>
                <button type="submit" class="btn btn-success fw-bold px-4">Update Publication Info</button>
            </form>

        <!-- TAB 9: DOI -->
        @elseif($activeTab === 'doi')
            <h5 class="fw-bold mb-3">DOI Configuration & Crossref Badge</h5>
            <form action="{{ route('admin.articles.update-publication', $article->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Assigned DOI String</label>
                    <input type="text" name="doi" value="{{ $article->doi }}" class="form-control font-monospace" placeholder="10.5281/zenodo.1026001">
                </div>
                <button type="submit" class="btn btn-primary fw-bold px-4">Save DOI</button>
            </form>
        @endif

    </div>
</div>
@endsection
