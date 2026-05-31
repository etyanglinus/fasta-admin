@extends('layouts.landing.app')

@section('title', $page->meta_title ?? $page->title)
@section('meta_description', $page->meta_description)

@section('content')
    <section class="page-hero">
        <div class="container">
            <h1>{{ $page->title }}</h1>
            <div class="breadcrumb">
                <a href="{{ route('home') }}">{{ translate('messages.home') }}</a> / {{ $page->title }}
            </div>
        </div>
    </section>

    <section class="page-content">
        <div class="container">
            <div class="content-card">
                @if($page->short_description)
                    <p class="lead">{{ $page->short_description }}</p>
                @endif
                <div>{!! $page->content !!}</div>
            </div>
        </div>
    </section>
@endsection
