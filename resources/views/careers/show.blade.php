@extends('layouts.landing.app')

@section('title', $job->title)

@section('content')
    <section class="page-hero">
        <div class="container">
            <h1>{{ $job->title }}</h1>
            <div class="breadcrumb">
                <a href="{{ route('home') }}">{{ translate('messages.home') }}</a> /
                <a href="{{ route('careers.index') }}">Careers</a> / {{ $job->title }}
            </div>
        </div>
    </section>

    <section class="page-content">
        <div class="container">
            <div class="content-card">
                <p class="text-muted">{{ $job->location }} - {{ $job->department }}</p>
                <div>{!! nl2br(e($job->description)) !!}</div>
                <hr>
                <h4>Apply for this role</h4>
                <form action="{{ route('careers.apply', $job->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="tel" name="phone" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Resume</label>
                        <input type="file" name="resume" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea name="cover_letter" class="form-control" rows="4"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Application</button>
                </form>
            </div>
        </div>
    </section>
@endsection
