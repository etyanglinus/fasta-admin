@extends('layouts.admin.app')

@section('title', 'Job Applications')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <h1 class="page-header-title">Job Applications</h1>
    </div>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                <thead class="thead-light">
                    <tr>
                        <th>Applicant</th>
                        <th>Opening</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Applied</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $application)
                    <tr>
                        <td>{{ $application->applicant_name }}</td>
                        <td>{{ optional($application->jobOpening)->title }}</td>
                        <td>{{ $application->email }}</td>
                        <td>{{ ucfirst($application->status) }}</td>
                        <td>{{ $application->created_at->format('Y-m-d') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.business-settings.cms.job-applications.show', $application) }}" class="btn btn-sm btn--primary">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $applications->links() }}
        </div>
    </div>
</div>
@endsection
