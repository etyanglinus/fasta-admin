@extends('layouts.landing.app')

@section('title', 'Careers')
@section('meta_description', 'Careers at Fasta Deliveries Kenya - join a local commerce, grocery delivery, logistics, and technology team building for Nairobi and beyond.')

@section('content')
    <section class="career-hero">
        <div class="container">
            <div class="career-hero__copy">
                <span class="career-kicker">Careers</span>
                <h1>Build the future of local commerce with us.</h1>
                <p>Join a team solving real city problems: reliable grocery access, vendor growth, last-mile delivery, and practical technology for Kenyan neighborhoods.</p>
                <a href="#open-positions" class="career-btn career-btn--primary">View open roles</a>
            </div>
            <div class="career-hero__panel">
                <strong>What we value</strong>
                <ul>
                    <li>Ownership over titles</li>
                    <li>Clear communication</li>
                    <li>Respect for customers, vendors, and riders</li>
                    <li>Fast learning with grounded execution</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="career-section">
        <div class="container">
            <div class="career-section__head">
                <span class="career-kicker">Why Join Us</span>
                <h2>Work that touches everyday life.</h2>
                <p>Our work is operational, technical, and deeply local. You will help build tools and services that vendors and customers depend on every day.</p>
            </div>
            <div class="career-feature-grid">
                <div class="career-feature"><h3>Meaningful local impact</h3><p>Support SMEs, fresh-food sellers, and delivery partners across active service areas.</p></div>
                <div class="career-feature"><h3>Practical innovation</h3><p>Build around payment habits, traffic, stock availability, and real customer behavior.</p></div>
                <div class="career-feature"><h3>Growth culture</h3><p>Work with people who care about execution, feedback, and improving week by week.</p></div>
            </div>
        </div>
    </section>

    <section class="career-section career-benefits">
        <div class="container">
            <div class="career-section__head">
                <span class="career-kicker">Benefits</span>
                <h2>A professional environment with room to grow.</h2>
            </div>
            <div class="career-benefit-grid">
                <span>Learning and mentorship</span>
                <span>Cross-functional projects</span>
                <span>Customer-first product work</span>
                <span>Operations exposure</span>
                <span>Inclusive team culture</span>
                <span>Local market experience</span>
            </div>
        </div>
    </section>

    <section class="career-section" id="open-positions">
        <div class="container">
            <div class="career-section__head">
                <span class="career-kicker">Open Positions</span>
                <h2>Find your next role.</h2>
            </div>
            <div class="career-job-list">
                @forelse($jobs as $opening)
                    <article class="career-job-card">
                        <div>
                            <h3>{{ $opening->title }}</h3>
                            <div class="career-job-meta">
                                @if($opening->department)<span>{{ $opening->department }}</span>@endif
                                @if($opening->location)<span>{{ $opening->location }}</span>@endif
                                @if($opening->employment_type)<span>{{ $opening->employment_type }}</span>@endif
                            </div>
                            @if($opening->description)
                                <p>{{ \Illuminate\Support\Str::limit(strip_tags($opening->description), 160) }}</p>
                            @endif
                        </div>
                        <a href="{{ route('careers.show', $opening->id) }}" class="career-btn career-btn--light">View role</a>
                    </article>
                @empty
                    <div class="career-empty">
                        <h3>No open roles right now.</h3>
                        <p>We are always growing. Check back soon or follow our updates for future opportunities.</p>
                    </div>
                @endforelse
            </div>
            @if(method_exists($jobs, 'links'))
                <div class="career-pagination">{{ $jobs->links() }}</div>
            @endif
        </div>
    </section>
@endsection
