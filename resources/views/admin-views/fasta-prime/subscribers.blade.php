@extends('layouts.admin.app')

@section('title', translate('Fasta Prime Subscribers'))
@section('subscription') active @endsection

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm">
                <h1 class="page-header-title">{{ translate('Fasta Prime Subscribers') }}</h1>
            </div>
            <div class="col-sm-auto">
                <a href="{{ route('admin.business-settings.fasta-prime.index') }}" class="btn btn--primary">{{ translate('Prime Plans') }}</a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <form class="w-100" method="get">
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="{{ translate('Search customer') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">{{ translate('All status') }}</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ translate('Active') }}</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>{{ translate('Expired') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn--primary btn-block">{{ translate('Filter') }}</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-borderless table-thead-bordered table-align-middle">
                <thead class="thead-light">
                    <tr>
                        <th>{{ translate('Customer') }}</th>
                        <th>{{ translate('Plan') }}</th>
                        <th>{{ translate('Paid') }}</th>
                        <th>{{ translate('Payment') }}</th>
                        <th>{{ translate('Ends') }}</th>
                        <th>{{ translate('Status') }}</th>
                        <th class="text-right">{{ translate('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subscriptions as $subscription)
                        <tr>
                            <td>
                                <strong>{{ trim(($subscription->user?->f_name ?? '') . ' ' . ($subscription->user?->l_name ?? '')) ?: translate('Customer') }}</strong>
                                <div class="text-muted">{{ $subscription->user?->phone ?? $subscription->user?->email }}</div>
                            </td>
                            <td>{{ $subscription->plan?->name ?? data_get($subscription->plan_snapshot, 'name') }}</td>
                            <td>{{ \App\CentralLogics\Helpers::format_currency($subscription->paid_amount) }}</td>
                            <td>{{ $subscription->payment_method }} / {{ $subscription->payment_status }}</td>
                            <td>{{ $subscription->end_date ? $subscription->end_date->format('Y-m-d') : '-' }}</td>
                            <td>
                                <span class="badge {{ $subscription->status && $subscription->end_date >= now() && !$subscription->is_canceled ? 'badge-success' : 'badge-secondary' }}">
                                    {{ $subscription->status && $subscription->end_date >= now() && !$subscription->is_canceled ? translate('Active') : translate('Inactive') }}
                                </span>
                            </td>
                            <td class="text-right">
                                @if($subscription->status && !$subscription->is_canceled)
                                    <form action="{{ route('admin.business-settings.fasta-prime.cancel', $subscription->id) }}" method="post">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger">{{ translate('Cancel') }}</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $subscriptions->links() }}</div>
    </div>
</div>
@endsection
