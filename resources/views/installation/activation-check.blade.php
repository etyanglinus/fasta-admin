@extends('installation.layouts.master')

@section('content')
    <div class="main-content">
        <div class="d-flex justify-content-center align-items-center min-vh-100">
            <div class="card p-4 text-center">
                <h4 class="mb-2">Activation is enabled</h4>
                <p class="mb-3">This customized build does not require external purchase-code verification.</p>
                <a class="btn btn-dark" href="{{ url('/') }}">Continue</a>
            </div>
        </div>
    </div>
@endsection