@extends('layouts.vendor.app')

@section('title', translate('Store Analytics'))

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm">
                <h1 class="page-header-title">
                    <i class="tio-chart-bar-4"></i> {{ translate('Store Analytics') }}
                </h1>
                <p class="page-header-text">{{ translate('Daily visitors from app, web, and custom domains.') }}</p>
            </div>
        </div>
    </div>

    <form method="get" class="card mb-3">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label>{{ translate('From') }}</label>
                    <input type="date" name="from" class="form-control" value="{{ $from->toDateString() }}">
                </div>
                <div class="col-md-4">
                    <label>{{ translate('To') }}</label>
                    <input type="date" name="to" class="form-control" value="{{ $to->toDateString() }}">
                </div>
                <div class="col-md-2">
                    <button class="btn btn--primary btn-block">{{ translate('Filter') }}</button>
                </div>
            </div>
        </div>
    </form>

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card h-100"><div class="card-body">
                <div class="text-muted">{{ translate('Today') }}</div>
                <h2 class="mb-0">{{ number_format($todayVisits) }}</h2>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card h-100"><div class="card-body">
                <div class="text-muted">{{ translate('Total') }}</div>
                <h2 class="mb-0">{{ number_format($totalVisits) }}</h2>
            </div></div>
        </div>
        @foreach(['app' => 'App', 'web' => 'Web', 'custom_domain' => 'Custom Domain'] as $source => $label)
            <div class="col-md-2">
                <div class="card h-100"><div class="card-body">
                    <div class="text-muted">{{ translate($label) }}</div>
                    <h3 class="mb-0">{{ number_format($sourceTotals[$source] ?? 0) }}</h3>
                </div></div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-borderless table-thead-bordered table-align-middle">
                <thead class="thead-light">
                    <tr>
                        <th>{{ translate('Date') }}</th>
                        <th>{{ translate('App') }}</th>
                        <th>{{ translate('Web') }}</th>
                        <th>{{ translate('Custom Domain') }}</th>
                        <th>{{ translate('Total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($daily as $date => $items)
                        <tr>
                            <td>{{ $date }}</td>
                            <td>{{ number_format($items->where('source', 'app')->sum('visit_count')) }}</td>
                            <td>{{ number_format($items->where('source', 'web')->sum('visit_count')) }}</td>
                            <td>{{ number_format($items->where('source', 'custom_domain')->sum('visit_count')) }}</td>
                            <td>{{ number_format($items->sum('visit_count')) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">{{ translate('No data found') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
