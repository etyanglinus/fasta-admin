@extends('layouts.admin.app')

@section('title', 'Application Details')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <h1 class="page-header-title">Application Details</h1>
    </div>
    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Applicant Name</dt>
                <dd class="col-sm-9">{{ $application->applicant_name }}</dd>

                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9">{{ $application->email }}</dd>

                <dt class="col-sm-3">Phone</dt>
                <dd class="col-sm-9">{{ $application->phone }}</dd>

                <dt class="col-sm-3">Job Opening</dt>
                <dd class="col-sm-9">{{ optional($application->jobOpening)->title }}</dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">{{ ucfirst($application->status) }}</dd>

                <dt class="col-sm-3">Applied At</dt>
                <dd class="col-sm-9">{{ $application->created_at->format('Y-m-d H:i') }}</dd>

                <dt class="col-sm-3">Resume</dt>
                <dd class="col-sm-9">
                    @if($application->resume)
                        <a href="{{ asset('storage/'.$application->resume) }}" target="_blank">Download Resume</a>
                    @else
                        N/A
                    @endif
                </dd>

                <dt class="col-sm-3">Message</dt>
                <dd class="col-sm-9">{{ $application->message }}</dd>
            </dl>
            <a href="{{ route('admin.business-settings.cms.job-applications.index') }}" class="btn btn--secondary">Back to Applications</a>
        </div>
    </div>
</div>
@endsection
