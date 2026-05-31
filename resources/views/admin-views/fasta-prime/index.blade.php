@extends('layouts.admin.app')

@section('title', translate('Fasta Prime'))
@section('subscription') active @endsection

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm">
                <h1 class="page-header-title">
                    <span class="page-header-icon"><i class="tio-crown"></i></span>
                    {{ translate('Fasta Prime') }}
                </h1>
                <p class="page-header-text">{{ translate('Create customer membership plans for free delivery and loyalty benefits.') }}</p>
            </div>
            <div class="col-sm-auto">
                <a href="{{ route('admin.business-settings.fasta-prime.subscribers') }}" class="btn btn--primary">{{ translate('Subscribers') }}</a>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ $editingPlan ? translate('Edit Prime Plan') : translate('Add Prime Plan') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ $editingPlan ? route('admin.business-settings.fasta-prime.update', $editingPlan->id) : route('admin.business-settings.fasta-prime.store') }}" method="post">
                @csrf
                @if($editingPlan)
                    @method('put')
                @endif
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ translate('Plan Name') }}</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $editingPlan?->name) }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ translate('Price') }} ({{ \App\CentralLogics\Helpers::currency_symbol() }})</label>
                            <input type="number" name="price" class="form-control" min="0" step="0.001" value="{{ old('price', $editingPlan?->price ?? 0) }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ translate('Billing Period') }}</label>
                            <select name="billing_period" class="form-control">
                                @foreach(['weekly' => 7, 'monthly' => 30, 'yearly' => 365] as $period => $days)
                                    <option value="{{ $period }}" data-days="{{ $days }}" {{ old('billing_period', $editingPlan?->billing_period ?? 'monthly') == $period ? 'selected' : '' }}>{{ translate(ucfirst($period)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ translate('Validity Days') }}</label>
                            <input type="number" name="validity_days" class="form-control" min="1" value="{{ old('validity_days', $editingPlan?->validity_days ?? 30) }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ translate('Delivery Charge Limit') }}</label>
                            <input type="number" name="free_delivery_limit" class="form-control" min="0" step="0.001" value="{{ old('free_delivery_limit', $editingPlan?->free_delivery_limit) }}" placeholder="{{ translate('Blank means no limit') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{ translate('Free Deliveries Limit') }}</label>
                            <input type="number" name="max_free_deliveries" class="form-control" min="1" value="{{ old('max_free_deliveries', $editingPlan?->max_free_deliveries) }}" placeholder="{{ translate('Blank means unlimited') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ translate('Description') }}</label>
                            <textarea name="description" class="form-control" rows="4">{{ old('description', $editingPlan?->description) }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ translate('Features') }}</label>
                            <textarea name="features" class="form-control" rows="4" placeholder="{{ translate('One feature per line') }}">{{ old('features', $editingPlan ? implode("\n", $editingPlan->features ?? []) : "Free delivery on eligible orders\nPriority support\nExclusive member offers") }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-12 d-flex flex-wrap gap-3">
                        <label class="form-check form--check">
                            <input type="checkbox" class="form-check-input" name="free_delivery" {{ old('free_delivery', $editingPlan?->free_delivery ?? 1) ? 'checked' : '' }}>
                            <span class="form-check-label">{{ translate('Enable free delivery') }}</span>
                        </label>
                        <label class="form-check form--check">
                            <input type="checkbox" class="form-check-input" name="status" {{ old('status', $editingPlan?->status ?? 1) ? 'checked' : '' }}>
                            <span class="form-check-label">{{ translate('Active') }}</span>
                        </label>
                    </div>
                </div>
                <div class="btn--container justify-content-end mt-3">
                    @if($editingPlan)
                        <a href="{{ route('admin.business-settings.fasta-prime.index') }}" class="btn btn--reset">{{ translate('Cancel') }}</a>
                    @endif
                    <button type="submit" class="btn btn--primary">{{ translate('Save') }}</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-borderless table-thead-bordered table-align-middle">
                <thead class="thead-light">
                    <tr>
                        <th>{{ translate('Plan') }}</th>
                        <th>{{ translate('Price') }}</th>
                        <th>{{ translate('Validity') }}</th>
                        <th>{{ translate('Subscribers') }}</th>
                        <th>{{ translate('Status') }}</th>
                        <th class="text-right">{{ translate('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($plans as $plan)
                        <tr>
                            <td>
                                <strong>{{ $plan->name }}</strong>
                                <div class="text-muted">{{ $plan->billing_period }}</div>
                            </td>
                            <td>{{ \App\CentralLogics\Helpers::format_currency($plan->price) }}</td>
                            <td>{{ $plan->validity_days }} {{ translate('days') }}</td>
                            <td>{{ $plan->active_subscriptions_count }} / {{ $plan->subscriptions_count }}</td>
                            <td>
                                <a href="{{ route('admin.business-settings.fasta-prime.status', $plan->id) }}" class="badge {{ $plan->status ? 'badge-success' : 'badge-danger' }}">{{ $plan->status ? translate('Active') : translate('Inactive') }}</a>
                            </td>
                            <td class="text-right">
                                <a class="btn btn-sm btn--primary" href="{{ route('admin.business-settings.fasta-prime.index', ['edit' => $plan->id]) }}">{{ translate('Edit') }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $plans->links() }}</div>
    </div>
</div>
@endsection
