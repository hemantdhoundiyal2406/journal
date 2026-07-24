@extends('layouts.admin')

@section('title', 'Email Templates Management')
@section('page_header', 'Automated Email Templates')

@section('content')
<div class="card bg-white border-0 shadow-sm rounded-4 p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th>Template Name</th>
                    <th>Subject Line</th>
                    <th>Available Dynamic Placeholders</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($templates as $tpl)
                    <tr>
                        <td class="fw-bold text-dark">{{ $tpl->name }}</td>
                        <td>{{ $tpl->subject }}</td>
                        <td class="small"><code class="text-primary">{{ $tpl->placeholders }}</code></td>
                        <td class="text-end">
                            <a href="{{ route('admin.email-templates.edit', $tpl->id) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil-square me-1"></i> Edit Template
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
