@extends('layouts.landing.app')

@section('title', $pressRelease->title)
@section('meta_description', $pressRelease->summary)

@section('content')
    <section class="press-detail-hero">
        <div class="container">
            <a href="{{ route('press.index') }}" class="press-back">Back to press</a>
            <span class="press-kicker">{{ optional($pressRelease->publish_date)->format('M d, Y') ?: 'Press Release' }}</span>
            <h1>{{ $pressRelease->title }}</h1>
            @if($pressRelease->summary)
                <p>{{ $pressRelease->summary }}</p>
            @endif
        </div>
    </section>

    <section class="press-detail-section">
        <div class="container">
            <article class="press-detail-card">
                @if($pressRelease->featured_image)
                    <img src="{{ asset('storage/'.$pressRelease->featured_image) }}" alt="{{ $pressRelease->title }}">
                @endif
                <div class="press-richtext">{!! $pressRelease->content !!}</div>
                @if($pressRelease->pdf_file)
                    <a href="{{ asset('storage/'.$pressRelease->pdf_file) }}" class="press-btn press-btn--primary" target="_blank">Download PDF</a>
                @endif
            </article>
        </div>
    </section>
@endsection
