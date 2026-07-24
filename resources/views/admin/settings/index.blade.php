@extends('layouts.admin')

@section('title', 'Website & SEO Settings')
@section('page_header', 'Website & SEO Configuration')

@section('content')
<div class="card bg-white border-0 shadow-sm rounded-4 p-4">
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <ul class="nav nav-tabs mb-4" id="settingTabs" role="tablist">
            <li class="nav-item"><button class="nav-link active fw-bold" data-bs-toggle="tab" data-bs-target="#generalTab" type="button">General & Metadata</button></li>
            <li class="nav-item"><button class="nav-link fw-bold" data-bs-toggle="tab" data-bs-target="#seoTab" type="button">SEO Tags & Schema</button></li>
            <li class="nav-item"><button class="nav-link fw-bold" data-bs-toggle="tab" data-bs-target="#contactTab" type="button">Contact & Editorial Info</button></li>
            <li class="nav-item"><button class="nav-link fw-bold" data-bs-toggle="tab" data-bs-target="#heroTab" type="button">Homepage Hero Banner</button></li>
        </ul>

        <div class="tab-content" id="settingTabsContent">
            <!-- General Tab -->
            <div class="tab-pane fade show active" id="generalTab">
                <div class="row g-3 mb-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Journal Full Name</label>
                        <input type="text" name="journal_name" value="{{ $settings['journal_name'] ?? '' }}" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Short Prefix</label>
                        <input type="text" name="manuscript_id_prefix" value="{{ $settings['manuscript_id_prefix'] ?? 'IJASER' }}" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Journal Tagline</label>
                    <input type="text" name="tagline" value="{{ $settings['tagline'] ?? '' }}" class="form-control">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Print ISSN</label>
                        <input type="text" name="print_issn" value="{{ $settings['print_issn'] ?? '' }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Online E-ISSN</label>
                        <input type="text" name="online_issn" value="{{ $settings['online_issn'] ?? '' }}" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Publisher Name</label>
                    <input type="text" name="publisher_name" value="{{ $settings['publisher_name'] ?? '' }}" class="form-control">
                </div>
            </div>

            <!-- SEO Tab -->
            <div class="tab-pane fade" id="seoTab">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Global Meta Title</label>
                    <input type="text" name="meta_title" value="{{ $settings['meta_title'] ?? '' }}" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Global Meta Keywords</label>
                    <input type="text" name="meta_keywords" value="{{ $settings['meta_keywords'] ?? '' }}" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Global Meta Description</label>
                    <textarea name="meta_description" rows="4" class="form-control">{{ $settings['meta_description'] ?? '' }}</textarea>
                </div>
            </div>

            <!-- Contact Tab -->
            <div class="tab-pane fade" id="contactTab">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Editorial Contact Email</label>
                        <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? '' }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Contact Phone</label>
                        <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] ?? '' }}" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Editorial Office Address</label>
                    <input type="text" name="contact_address" value="{{ $settings['contact_address'] ?? '' }}" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Editor-in-Chief Name</label>
                    <input type="text" name="editor_in_chief" value="{{ $settings['editor_in_chief'] ?? '' }}" class="form-control">
                </div>
            </div>

            <!-- Hero Banner Tab -->
            <div class="tab-pane fade" id="heroTab">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Hero Main Title</label>
                    <input type="text" name="hero_title" value="{{ $settings['hero_title'] ?? '' }}" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Hero Subtitle</label>
                    <textarea name="hero_subtitle" rows="3" class="form-control">{{ $settings['hero_subtitle'] ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <div class="border-top pt-3 mt-4">
            <button type="submit" class="btn btn-primary btn-lg fw-bold px-5">Save All Settings</button>
        </div>
    </form>
</div>
@endsection
