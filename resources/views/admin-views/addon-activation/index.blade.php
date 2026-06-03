@extends('layouts.admin.app')

@section('title', translate('messages.Addon_Activation'))

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <h1 class="page-header-title">{{ translate('messages.Addon_Activation') }}</h1>
        </div>

        <div class="card">
            <div class="card-body text-center py-5">
                <h3 class="mb-2">{{ translate('messages.All addons are active') }}</h3>
                <p class="text-muted mb-0">{{ translate('messages.Installed apps and addons are enabled by default for this customized build.') }}</p>
            </div>
        </div>
    </div>
@endsection