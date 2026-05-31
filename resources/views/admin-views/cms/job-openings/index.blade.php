@extends('layouts.admin.app')

@section('title', 'Job Openings')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <h1 class="page-header-title">Job Openings</h1>
        <div>
            <a href="{{ route('admin.business-settings.cms.job-openings.create') }}" class="btn btn--primary">Create Opening</a>
            <a href="{{ route('admin.business-settings.cms.job-applications.index') }}" class="btn btn--secondary">Applications</a>
        </div>
    </div>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                <thead class="thead-light">
                    <tr>
                        <th>Title</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Posted</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($openings as $opening)
                    <tr>
                        <td>{{ $opening->title }}</td>
                        <td>{{ $opening->location }}</td>
                        <td>{{ $opening->status ? 'Open' : 'Closed' }}</td>
                        <td>{{ $opening->created_at->format('Y-m-d') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.business-settings.cms.job-openings.edit', $opening) }}" class="btn btn-sm btn--primary">Edit</a>
                            <form action="{{ route('admin.business-settings.cms.job-openings.destroy', $opening) }}" method="post" class="d-inline-block">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-sm btn--danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $openings->links() }}
        </div>
    </div>
</div>
@endsection
