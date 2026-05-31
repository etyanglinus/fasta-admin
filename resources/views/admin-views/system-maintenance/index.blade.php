@extends('layouts.admin.app')

@section('title', translate('System Maintenance'))

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <h1 class="page-header-title">
            <i class="tio-settings"></i> {{ translate('System Maintenance') }}
        </h1>
        <p class="page-header-text">{{ translate('Review system logs and create database backups.') }}</p>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ translate('Employee & Seller Login Activity') }}</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-borderless table-thead-bordered table-align-middle">
                <thead class="thead-light">
                    <tr>
                        <th>{{ translate('User') }}</th>
                        <th>{{ translate('Type') }}</th>
                        <th>{{ translate('IP Address') }}</th>
                        <th>{{ translate('Device') }}</th>
                        <th>{{ translate('OS') }}</th>
                        <th>{{ translate('Browser') }}</th>
                        <th>{{ translate('Logged In') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loginLogs as $entry)
                        <tr>
                            <td>
                                <strong>{{ $entry->name ?: translate('Unknown') }}</strong>
                                <div class="text-muted">{{ $entry->email }}</div>
                            </td>
                            <td><span class="badge badge-soft-info">{{ str_replace('_', ' ', $entry->user_type) }}</span></td>
                            <td>{{ $entry->ip_address }}</td>
                            <td>{{ $entry->device_type }}</td>
                            <td>{{ $entry->os }}</td>
                            <td>{{ $entry->browser }}</td>
                            <td>{{ $entry->logged_in_at?->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">{{ translate('No login activity recorded yet') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ translate('Latest Logs') }}</h5>
                    <form action="{{ route('admin.business-settings.system-maintenance.clear-log') }}" method="post">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger">{{ translate('Clear Log') }}</button>
                    </form>
                </div>
                <div class="card-body">
                    <pre class="p-3 rounded bg-dark text-white mb-0" style="max-height: 520px; overflow:auto; white-space: pre-wrap;">{{ $log ?: translate('No log entries found') }}</pre>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ translate('Database Backups') }}</h5>
                    <form action="{{ route('admin.business-settings.system-maintenance.backup') }}" method="post">
                        @csrf
                        <button class="btn btn--primary btn-sm">{{ translate('Create Backup') }}</button>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-borderless table-thead-bordered table-align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ translate('File') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th>{{ translate('Size') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($backups as $backup)
                                <tr>
                                    <td>
                                        <strong>{{ $backup->file_name }}</strong>
                                        <div class="text-muted">{{ $backup->created_at }}</div>
                                    </td>
                                    <td><span class="badge {{ $backup->status === 'completed' ? 'badge-success' : 'badge-danger' }}">{{ $backup->status }}</span></td>
                                    <td>{{ number_format($backup->size / 1024, 1) }} KB</td>
                                    <td class="text-right">
                                        @if($backup->status === 'completed')
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.business-settings.system-maintenance.download', $backup->id) }}">{{ translate('Download') }}</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">{{ $backups->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
