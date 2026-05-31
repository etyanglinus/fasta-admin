@extends('layouts.landing.app')

@section('title', 'Blog')

@section('content')
    <section class="page-hero">
        <div class="container">
            <h1>Blog</h1>
            <div class="breadcrumb">
                <a href="{{ route('home') }}">{{ translate('messages.home') }}</a> / Blog
            </div>
        </div>
    </section>

    <section class="page-content">
        <div class="container">
            <div class="content-card">
                <p class="text-muted">Latest stories, news, and updates.</p>
                <div class="row">
                    @forelse($latest as $post)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            @if($post->featured_image)
                                <img src="{{ asset('storage/'.$post->featured_image) }}" class="card-img-top" alt="{{ $post->title }}">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $post->title }}</h5>
                                <p class="card-text">{{ \Illuminate\Support\Str::limit($post->excerpt ?? strip_tags($post->content), 120) }}</p>
                                <a href="{{ route('blog.show', $post->slug) }}" class="mt-auto btn btn-primary">Read More</a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <p class="mb-0">No blog posts found.</p>
                    </div>
                    @endforelse
                </div>
                @if(method_exists($latest, 'links'))
                    <div class="mt-4">{{ $latest->links() }}</div>
                @endif
            </div>
        </div>
    </section>
@endsection
