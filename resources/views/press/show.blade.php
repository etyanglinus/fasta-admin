@extends('layouts.landing.app')

@section('title', $pressRelease->title)

@section('content')
    <section class="page-hero">
        <div class="container">
            <h1>{{ $pressRelease->title }}</h1>
            <div class="breadcrumb">
                <a href="{{ route('home') }}">{{ translate('messages.home') }}</a> /
                <a href="{{ route('press.index') }}">Press & Media</a> / {{ $pressRelease->title }}
            </div>
        </div>
    </section>

    <section class="page-content">
        <div class="container">
            <div class="content-card">
                <p class="text-muted">{{ optional($pressRelease->publish_date)->format('M d, Y') }}</p>
                @if($pressRelease->featured_image)
                    <img src="{{ asset('storage/'.$pressRelease->featured_image) }}" class="img-fluid mb-4" alt="{{ $pressRelease->title }}">
                @endif
                <div>{!! $pressRelease->content !!}</div>
                @if($pressRelease->pdf_file)
                    <a href="{{ asset('storage/'.$pressRelease->pdf_file) }}" class="btn btn-primary mt-3" target="_blank">Download PDF</a>
                @endif
            </div>
        </div>
    </section>
@endsection
