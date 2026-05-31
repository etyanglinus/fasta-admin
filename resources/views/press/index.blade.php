@extends('layouts.landing.app')

@section('title', 'Press & Media')
@section('meta_description', 'Press and media resources for Fasta Deliveries Kenya, including company announcements, press releases, media kit downloads, and brand assets.')

@section('content')
    <section class="press-hero">
        <div class="container">
            <div class="press-hero__copy">
                <span class="press-kicker">Press & Media</span>
                <h1>News, updates, and resources for media partners.</h1>
                <p>Follow our progress as we build practical local commerce infrastructure for customers, vendors, and delivery partners in Kenya.</p>
            </div>
            <div class="press-hero__panel">
                <strong>Media enquiries</strong>
                <p>For interviews, brand resources, or company information, use the media kit below or contact our team through the main contact channels.</p>
                <a href="#media-kit" class="press-btn press-btn--primary">View media kit</a>
            </div>
        </div>
    </section>

    <section class="press-section">
        <div class="container">
            <div class="press-section__head">
                <span class="press-kicker">Latest Releases</span>
                <h2>Company announcements</h2>
            </div>
            <div class="press-release-grid">
                @forelse($pressReleases as $release)
                    <article class="press-release-card">
                        @if($release->featured_image)
                            <img src="{{ asset('storage/'.$release->featured_image) }}" alt="{{ $release->title }}">
                        @endif
                        <div class="press-release-card__body">
                            <span>{{ optional($release->publish_date)->format('M d, Y') ?: 'Announcement' }}</span>
                            <h3>{{ $release->title }}</h3>
                            @if($release->summary)
                                <p>{{ $release->summary }}</p>
                            @endif
                            <a href="{{ route('press.show', $release->slug) }}" class="press-btn press-btn--light">Read release</a>
                        </div>
                    </article>
                @empty
                    <div class="press-empty">
                        <h3>No press releases yet.</h3>
                        <p>Announcements will appear here when they are published from the admin panel.</p>
                    </div>
                @endforelse
            </div>
            @if(method_exists($pressReleases, 'links'))
                <div class="press-pagination">{{ $pressReleases->links() }}</div>
            @endif
        </div>
    </section>

    <section class="press-section press-kit" id="media-kit">
        <div class="container">
            <div class="press-section__head">
                <span class="press-kicker">Media Kit</span>
                <h2>Download approved brand and media assets.</h2>
            </div>
            <div class="press-asset-grid">
                @forelse($assets as $asset)
                    <article class="press-asset-card">
                        <div>
                            <span>{{ $asset->type ?: 'Asset' }}</span>
                            <h3>{{ $asset->title }}</h3>
                        </div>
                        @if($asset->file)
                            <a href="{{ asset('storage/'.$asset->file) }}" target="_blank" class="press-btn press-btn--light">Download</a>
                        @endif
                    </article>
                @empty
                    <div class="press-empty">
                        <h3>No media assets uploaded.</h3>
                        <p>Add logos, founder photos, fact sheets, or brand guidelines from the admin Media Assets section.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
