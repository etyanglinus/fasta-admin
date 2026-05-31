@extends('layouts.landing.app')

@section('title', translate('messages.about_us'))
@section('meta_description', 'About 6amMart Kenya - hyperlocal delivery in Nairobi, supporting local vendors, reliable grocery delivery, and community-first commerce.')

@section('content')
    @php
        $splitCards = function ($text) {
            return collect(preg_split('/\r\n|\r|\n/', trim($text ?? '')))
                ->filter()
                ->map(function ($line) {
                    $parts = array_map('trim', explode('|', $line, 2));
                    return [
                        'title' => $parts[0] ?? '',
                        'text' => $parts[1] ?? '',
                    ];
                })
                ->values();
        };

        $splitLines = function ($text) {
            return collect(preg_split('/\r\n|\r|\n/', trim($text ?? '')))->filter()->values();
        };

        $impactItems = $splitCards($about['impact_items'] ?? '');
        $valueItems = $splitCards($about['values_items'] ?? '');
        $milestoneItems = $splitLines($about['milestones_items'] ?? '');
        $coverageTags = $splitLines($about['coverage_tags'] ?? '');
    @endphp

    <section class="about-hero">
        <div class="container">
            <div class="about-hero__copy">
                <span class="about-kicker">{{ $about['hero_kicker'] }}</span>
                <h1>{{ $about['hero_title'] }}</h1>
                <p>{{ $about['hero_subtitle'] }}</p>
                <div class="about-hero__actions">
                    <a href="{{ route('restaurant.create') }}" class="about-btn about-btn--primary">{{ $about['primary_cta'] }}</a>
                    <a href="{{ route('careers.index') }}" class="about-btn about-btn--light">{{ $about['secondary_cta'] }}</a>
                </div>
            </div>
            <div class="about-hero__media">
                <img src="{{ $about['hero_image'] }}" alt="Local commerce and delivery partners">
                <div class="about-hero__note">
                    <strong>{{ $about['hero_note_title'] }}</strong>
                    <span>{{ $about['hero_note_text'] }}</span>
                </div>
            </div>
        </div>
    </section>

    <section class="about-section about-story">
        <div class="container">
            <div class="about-section__head">
                <span class="about-kicker">{{ $about['story_kicker'] }}</span>
                <h2>{{ $data_title ?: 'Local access should be fast, fair, and reliable.' }}</h2>
            </div>
            <div class="about-story__grid">
                <div class="about-story__content">
                    @if(!empty(trim(strip_tags($data ?? ''))))
                        {!! $data !!}
                    @else
                        <p>
                            6amMart Kenya was shaped by a simple problem: getting quality groceries, meals, and daily essentials across Nairobi should not depend on long queues, unpredictable traffic, or whether a neighborhood vendor has the tools to sell online.
                        </p>
                        <p>
                            We are building a practical bridge between customers, independent vendors, and delivery partners. The goal is not just faster orders. It is a stronger local commerce network where small businesses can compete, customers can trust what arrives, and riders can find dignified earning opportunities.
                        </p>
                    @endif
                </div>
                <div class="about-mission-card">
                    <h3>{{ $about['mission_label'] }}</h3>
                    <p>{{ $about['mission'] }}</p>
                    <h3>{{ $about['vision_label'] }}</h3>
                    <p>{{ $about['vision'] }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="about-section about-impact">
        <div class="container">
            <div class="about-section__head">
                <span class="about-kicker">{{ $about['impact_kicker'] }}</span>
                <h2>{{ $about['impact_title'] }}</h2>
            </div>
            <div class="about-impact__grid">
                @foreach($impactItems as $item)
                    <div class="impact-card">
                        <strong>{{ $item['title'] }}</strong>
                        <span>{{ $item['text'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="about-section about-values">
        <div class="container">
            <div class="about-section__head">
                <span class="about-kicker">{{ $about['values_kicker'] }}</span>
                <h2>{{ $about['values_title'] }}</h2>
            </div>
            <div class="about-values__grid">
                @foreach($valueItems as $item)
                    <div class="value-card"><h3>{{ $item['title'] }}</h3><p>{{ $item['text'] }}</p></div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="about-section about-coverage">
        <div class="container">
            <div class="about-coverage__panel">
                <div>
                    <span class="about-kicker">{{ $about['coverage_kicker'] }}</span>
                    <h2>{{ $about['coverage_title'] }}</h2>
                    <p>{{ $about['coverage_text'] }}</p>
                    <div class="coverage-tags">
                        @foreach($coverageTags as $tag)
                            <span>{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="coverage-map" aria-label="Coverage illustration">
                    <span>{{ $about['coverage_map_title'] }}</span>
                    <small>{{ $about['coverage_map_text'] }}</small>
                </div>
            </div>
        </div>
    </section>

    <section class="about-section about-timeline">
        <div class="container">
            <div class="about-section__head">
                <span class="about-kicker">{{ $about['milestones_kicker'] }}</span>
                <h2>{{ $about['milestones_title'] }}</h2>
            </div>
            <div class="timeline-list">
                @foreach($milestoneItems as $index => $milestone)
                    <div class="timeline-item"><span>{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span><p>{{ $milestone }}</p></div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="about-section about-team">
        <div class="container">
            <div class="about-section__head">
                <span class="about-kicker">{{ $about['team_kicker'] }}</span>
                <h2>{{ $about['team_title'] }}</h2>
            </div>
            @if(isset($teamMembers) && $teamMembers->count())
                <div class="about-team__grid">
                    @foreach($teamMembers as $member)
                        <article class="team-card">
                            <div class="team-card__photo">
                                @if($member->photo)
                                    <img src="{{ asset('storage/'.$member->photo) }}" alt="{{ $member->name }}">
                                @else
                                    <span>{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <h3>{{ $member->name }}</h3>
                            <strong>{{ $member->position }}</strong>
                            <p>{{ $member->bio }}</p>
                            @if($member->linkedin_url)
                                <a href="{{ $member->linkedin_url }}" target="_blank" rel="noopener">LinkedIn</a>
                            @endif
                        </article>
                    @endforeach
                </div>
            @else
                <div class="about-team__empty">
                    <h3>{{ $about['team_empty_title'] }}</h3>
                    <p>{{ $about['team_empty_text'] }}</p>
                </div>
            @endif
        </div>
    </section>

    <section class="about-section about-trust">
        <div class="container">
            <div class="about-trust__panel">
                <div>
                    <span class="about-kicker">{{ $about['trust_kicker'] }}</span>
                    <h2>{{ $about['trust_title'] }}</h2>
                    <p>{{ $about['trust_text'] }}</p>
                </div>
                <a href="{{ route('privacy-policy') }}" class="about-btn about-btn--primary">{{ $about['privacy_cta'] }}</a>
            </div>
        </div>
    </section>
@endsection
