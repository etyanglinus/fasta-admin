@extends('layouts.admin.app')

@section('title', 'Press Releases')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <h1 class="page-header-title">Press Releases</h1>
        <a href="{{ route('admin.business-settings.cms.press-releases.create') }}" class="btn btn--primary">Create Release</a>
    </div>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                <thead class="thead-light">
                    <tr>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pressReleases as $release)
                    <tr>
                        <td>{{ $release->title }}</td>
                        <td>{{ optional($release->publish_date)->format('Y-m-d') }}</td>
                        <td>{{ $release->status ? 'Published' : 'Draft' }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.business-settings.cms.press-releases.edit', $release) }}" class="btn btn-sm btn--primary">Edit</a>
                            <form action="{{ route('admin.business-settings.cms.press-releases.destroy', $release) }}" method="post" class="d-inline-block">
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
            {{ $pressReleases->links() }}
        </div>
    </div>
</div>
@endsection
