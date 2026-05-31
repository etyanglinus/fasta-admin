@extends('layouts.landing.app')

@section('title', 'Careers')

@section('content')
    <section class="page-hero">
        <div class="container">
            <h1>Careers</h1>
            <div class="breadcrumb">
                <a href="{{ route('home') }}">{{ translate('messages.home') }}</a> / Careers
            </div>
        </div>
    </section>

    <section class="page-content">
        <div class="container">
            <div class="content-card">
                <p class="text-muted">Explore current openings and grow with us.</p>
                <div class="row">
                    @forelse($jobs as $opening)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $opening->title }}</h5>
                                <p class="card-text">{{ $opening->location }} - {{ $opening->department }}</p>
                                <a href="{{ route('careers.show', $opening->id) }}" class="mt-auto btn btn-primary">View Opening</a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <p class="mb-0">No open positions found.</p>
                    </div>
                    @endforelse
                </div>
                <div class="mt-4">{{ $jobs->links() }}</div>
            </div>
        </div>
    </section>
@endsection
