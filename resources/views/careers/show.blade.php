@extends('layouts.landing.app')

@section('title', $job->title)

@section('content')
    <section class="career-detail-hero">
        <div class="container">
            <a href="{{ route('careers.index') }}" class="career-back">Back to careers</a>
            <h1>{{ $job->title }}</h1>
            <div class="career-job-meta career-job-meta--hero">
                @if($job->department)<span>{{ $job->department }}</span>@endif
                @if($job->location)<span>{{ $job->location }}</span>@endif
                @if($job->employment_type)<span>{{ $job->employment_type }}</span>@endif
                @if($job->closing_date)<span>Closes {{ $job->closing_date->format('M d, Y') }}</span>@endif
            </div>
        </div>
    </section>

    <section class="career-detail-section">
        <div class="container">
            <div class="career-detail-grid">
                <article class="career-detail-card">
                    <h2>Role overview</h2>
                    <div class="career-richtext">{!! nl2br(e($job->description)) !!}</div>

                    @if($job->responsibilities)
                        <h2>Responsibilities</h2>
                        <div class="career-richtext">{!! nl2br(e($job->responsibilities)) !!}</div>
                    @endif

                    @if($job->requirements)
                        <h2>Requirements</h2>
                        <div class="career-richtext">{!! nl2br(e($job->requirements)) !!}</div>
                    @endif
                </article>

                <aside class="career-apply-card">
                    <h2>Apply for this role</h2>
                    @if(session('success'))
                        <div class="career-success">{{ session('success') }}</div>
                    @endif
                    <form action="{{ route('careers.apply', $job->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="career-form-group">
                            <label>Name</label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" required>
                        </div>
                        <div class="career-form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required>
                        </div>
                        <div class="career-form-group">
                            <label>Phone</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}">
                        </div>
                        <div class="career-form-group">
                            <label>Resume</label>
                            <input type="file" name="resume" accept=".pdf,.doc,.docx">
                        </div>
                        <div class="career-form-group">
                            <label>Cover letter</label>
                            <textarea name="cover_letter" rows="4">{{ old('cover_letter') }}</textarea>
                        </div>
                        <button type="submit" class="career-btn career-btn--primary career-btn--full">Submit application</button>
                    </form>
                </aside>
            </div>
        </div>
    </section>
@endsection
