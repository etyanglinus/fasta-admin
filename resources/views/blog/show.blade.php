@extends('layouts.landing.app')

@section('title', $post->meta_title ?? $post->title)
@section('meta_description', $post->meta_description)

@section('content')
    <section class="page-hero">
        <div class="container">
            <h1>{{ $post->title }}</h1>
            <div class="breadcrumb">
                <a href="{{ route('home') }}">{{ translate('messages.home') }}</a> /
                <a href="{{ route('blog.index') }}">Blog</a> / {{ $post->title }}
            </div>
        </div>
    </section>

    <section class="page-content">
        <div class="container">
            <div class="content-card">
                <p class="text-muted">{{ optional($post->category)->name }} | {{ optional($post->published_at)->format('M d, Y') }}</p>
                @if($post->featured_image)
                    <img src="{{ asset('storage/'.$post->featured_image) }}" class="img-fluid mb-4" alt="{{ $post->title }}">
                @endif
                <div>{!! $post->content !!}</div>
            </div>
        </div>
    </section>
@endsection
