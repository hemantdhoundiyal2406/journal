@extends('layouts.frontend')

@section('title', $page->meta_title ?? $page->title . ' | IJASER Journal')
@section('meta_description', $page->meta_description)
@section('meta_keywords', $page->meta_keywords)

@section('content')
<div class="bg-white border-bottom py-4 mb-4">
    <div class="container">
        <h1 class="h2 fw-bold text-dark serif-font mb-0">{{ $page->title }}</h1>
    </div>
</div>

<div class="container pb-5">
    <div class="bg-white p-5 rounded-4 border shadow-sm">
        <div class="cms-content lh-lg">
            {!! $page->content !!}
        </div>
    </div>
</div>
@endsection
