@extends('layouts.landing.app')

@section('title', 'Press & Media')

@section('content')
    <section class="page-hero">
        <div class="container">
            <h1>Press & Media</h1>
            <div class="breadcrumb">
                <a href="{{ route('home') }}">{{ translate('messages.home') }}</a> / Press & Media
            </div>
        </div>
    </section>

    <section class="page-content">
        <div class="container">
            <div class="content-card">
                <p class="text-muted">Read our latest announcements, press releases, and media coverage.</p>
                <div class="row">
                    @forelse($pressReleases as $release)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $release->title }}</h5>
                                <p class="card-text">{{ optional($release->publish_date)->format('M d, Y') }}</p>
                                <a href="{{ route('press.show', $release->slug) }}" class="mt-auto btn btn-primary">Read Release</a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <p class="mb-0">No press releases found.</p>
                    </div>
                    @endforelse
                </div>
                <div class="mt-4">{{ $pressReleases->links() }}</div>
            </div>
        </div>
    </section>
@endsection
