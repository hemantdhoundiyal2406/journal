@extends('layouts.frontend')

@section('title', 'Submit Manuscript | ' . \App\Models\JournalSetting::getByKey('journal_name'))

@section('content')
<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <h1 class="h2 fw-bold text-dark serif-font mb-1"><i class="bi bi-cloud-arrow-up-fill text-primary me-2"></i> Online Manuscript Submission</h1>
        <p class="text-muted mb-0">Please fill in the paper details and author affiliations below. An automated Manuscript ID will be generated upon submission.</p>
    </div>
</div>

<div class="container pb-5">
    <form action="{{ route('submission.submit') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
        @csrf

        <div class="row g-4">
            <!-- Left Column: Article Metadata & Authors -->
            <div class="col-lg-8">

                <!-- 1. Article Details Card -->
                <div class="bg-white p-4 rounded-4 border shadow-sm mb-4">
                    <h4 class="h5 serif-font fw-bold mb-3 border-bottom pb-2 text-dark">1. Article Metadata</h4>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Article Full Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Enter the complete title of your research paper..." required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Running Short Title</label>
                            <input type="text" name="running_title" class="form-control" value="{{ old('running_title') }}" placeholder="Short title for header (optional)">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Subject Category <span class="text-danger">*</span></label>
                            <select name="category" class="form-select" required>
                                <option value="">Select Category</option>
                                <option value="Computer Science & Security">Computer Science & Security</option>
                                <option value="Engineering & Technology">Engineering & Technology</option>
                                <option value="Medical & Life Sciences">Medical & Life Sciences</option>
                                <option value="Environmental Sciences">Environmental Sciences</option>
                                <option value="Multidisciplinary Studies">Multidisciplinary Studies</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Article Type <span class="text-danger">*</span></label>
                            <select name="article_type" class="form-select" required>
                                <option value="Research Paper">Research Paper</option>
                                <option value="Review Paper">Review Paper</option>
                                <option value="Case Study">Case Study</option>
                                <option value="Short Communication">Short Communication</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Structured Abstract <span class="text-danger">*</span></label>
                        <textarea name="abstract" rows="6" class="form-control @error('abstract') is-invalid @enderror" placeholder="Abstract must contain background, methods, key results, and conclusion (150-300 words)..." required>{{ old('abstract') }}</textarea>
                        @error('abstract') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-semibold">Keywords (Comma Separated) <span class="text-danger">*</span></label>
                        <input type="text" name="keywords" class="form-control @error('keywords') is-invalid @enderror" value="{{ old('keywords') }}" placeholder="e.g. Artificial Intelligence, Neural Networks, Deep Learning, Cybersecurity" required>
                        @error('keywords') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- 2. Authors & Affiliations Card -->
                <div class="bg-white p-4 rounded-4 border shadow-sm mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                        <h4 class="h5 serif-font fw-bold mb-0 text-dark">2. Authors & Affiliations</h4>
                        <button type="button" id="addAuthorBtn" class="btn btn-sm btn-outline-primary rounded-pill">
                            <i class="bi bi-person-plus-fill me-1"></i> Add Co-Author
                        </button>
                    </div>

                    <div id="authorsContainer">
                        <!-- Primary Author (Index 0) -->
                        <div class="author-row bg-light p-3 rounded-3 border mb-3 position-relative">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-primary small">Author #1 (Primary)</span>
                                <div class="form-check form-check-inline mb-0">
                                    <input class="form-check-input" type="radio" name="corresponding_author_index" id="corr_0" value="0" checked>
                                    <label class="form-check-label small fw-semibold text-dark" for="corr_0">Corresponding Author</label>
                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="col-md-6">
                                    <input type="text" name="authors[0][first_name]" class="form-control form-control-sm" placeholder="First Name *" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="authors[0][last_name]" class="form-control form-control-sm" placeholder="Last Name *" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="email" name="authors[0][email]" class="form-control form-control-sm" placeholder="Email Address *" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="authors[0][mobile]" class="form-control form-control-sm" placeholder="Mobile/Phone Number">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="authors[0][institution]" class="form-control form-control-sm" placeholder="University / Institution *" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="authors[0][country]" class="form-control form-control-sm" placeholder="Country *" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="authors[0][orcid]" class="form-control form-control-sm" placeholder="ORCID iD (Optional)">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. File Uploads Card -->
                <div class="bg-white p-4 rounded-4 border shadow-sm mb-4">
                    <h4 class="h5 serif-font fw-bold mb-3 border-bottom pb-2 text-dark">3. Manuscript File Uploads</h4>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Upload Full Manuscript (PDF, DOC, DOCX) <span class="text-danger">*</span></label>
                        <input type="file" name="manuscript_file" class="form-control @error('manuscript_file') is-invalid @enderror" accept=".pdf,.doc,.docx" required>
                        <div class="form-text">Main paper document without author identification for double-blind review. Max size: 20MB.</div>
                        @error('manuscript_file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Cover Letter (PDF, DOCX)</label>
                            <input type="file" name="cover_letter" class="form-control" accept=".pdf,.doc,.docx">
                            <div class="form-text">Addressing Editor-in-Chief.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Supplementary Files (ZIP, Images)</label>
                            <input type="file" name="supplementary_files[]" class="form-control" multiple>
                            <div class="form-text">High-res figures, datasets, code.</div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label fw-semibold">Comments or Notes for Editor</label>
                        <textarea name="author_notes" rows="3" class="form-control" placeholder="Any special requests or reviewer exclusions..."></textarea>
                    </div>
                </div>

            </div>

            <!-- Right Column: Submission Terms & Submit Button -->
            <div class="col-lg-4">
                <div class="bg-white p-4 rounded-4 border shadow-sm sticky-top" style="top: 90px;">
                    <h5 class="serif-font fw-bold mb-3 border-bottom pb-2">Submission Checklist</h5>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="chk1" name="originality_confirmed" value="1" required>
                        <label class="form-check-label small" for="chk1">The manuscript is original and not under consideration elsewhere.</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="chk2" name="authors_approved" value="1" required>
                        <label class="form-check-label small" for="chk2">All co-authors have approved the paper submission.</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="chk3" name="references_confirmed" value="1" required>
                        <label class="form-check-label small" for="chk3">References follow standard APA/IEEE citation rules.</label>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold py-3 shadow-sm">
                            <i class="bi bi-send-fill me-2"></i> Submit Manuscript Now
                        </button>
                    </div>
                    <div class="text-center mt-3">
                        <small class="text-muted"><i class="bi bi-shield-lock-fill text-success me-1"></i> Secure SSL Encrypted Upload</small>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let authorCount = 1;

        $('#addAuthorBtn').click(function() {
            authorCount++;
            let idx = authorCount - 1;
            let html = `
                <div class="author-row bg-light p-3 rounded-3 border mb-3 position-relative" id="author_row_${idx}">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-author-btn" data-idx="${idx}"></button>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-primary small">Author #${authorCount}</span>
                        <div class="form-check form-check-inline mb-0">
                            <input class="form-check-input" type="radio" name="corresponding_author_index" id="corr_${idx}" value="${idx}">
                            <label class="form-check-label small fw-semibold text-dark" for="corr_${idx}">Corresponding Author</label>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <input type="text" name="authors[${idx}][first_name]" class="form-control form-control-sm" placeholder="First Name *" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="authors[${idx}][last_name]" class="form-control form-control-sm" placeholder="Last Name *" required>
                        </div>
                        <div class="col-md-6">
                            <input type="email" name="authors[${idx}][email]" class="form-control form-control-sm" placeholder="Email Address *" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="authors[${idx}][mobile]" class="form-control form-control-sm" placeholder="Mobile/Phone Number">
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="authors[${idx}][institution]" class="form-control form-control-sm" placeholder="University / Institution *" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="authors[${idx}][country]" class="form-control form-control-sm" placeholder="Country *" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="authors[${idx}][orcid]" class="form-control form-control-sm" placeholder="ORCID iD (Optional)">
                        </div>
                    </div>
                </div>
            `;
            $('#authorsContainer').append(html);
        });

        $(document).on('click', '.remove-author-btn', function() {
            let idx = $(this).data('idx');
            $('#author_row_' + idx).remove();
        });
    });
</script>
@endsection
