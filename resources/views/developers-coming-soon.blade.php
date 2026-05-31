@extends('layouts.landing.app')

@section('title', 'Developers')
@section('meta_description', 'Developer resources for Fasta Deliveries Kenya are coming soon.')

@section('content')
    <section class="coming-soon-page">
        <div class="container">
            <div class="coming-soon-card">
                <span>Developers</span>
                <h1>Developer resources are coming soon.</h1>
                <p>We are preparing APIs, integration notes, and platform resources for partners and builders.</p>
                <a href="{{ route('home') }}" class="coming-soon-btn">Back to home</a>
            </div>
        </div>
    </section>
@endsection
