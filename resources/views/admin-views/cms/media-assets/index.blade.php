@extends('layouts.admin.app')

@section('title', 'Media Assets')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <h1 class="page-header-title">Media Assets</h1>
        <a href="{{ route('admin.business-settings.cms.media-assets.create') }}" class="btn btn--primary">Add Asset</a>
    </div>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                <thead class="thead-light">
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assets as $asset)
                    <tr>
                        <td>{{ $asset->title }}</td>
                        <td>{{ $asset->type }}</td>
                        <td>{{ $asset->status ? 'Published' : 'Draft' }}</td>
                        <td class="text-end">
                            @if($asset->file)
                                <a href="{{ asset('storage/'.$asset->file) }}" target="_blank" class="btn btn-sm btn--secondary">View</a>
                            @endif
                            <a href="{{ route('admin.business-settings.cms.media-assets.edit', $asset) }}" class="btn btn-sm btn--primary">Edit</a>
                            <form action="{{ route('admin.business-settings.cms.media-assets.destroy', $asset) }}" method="post" class="d-inline-block">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-sm btn--danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No media assets found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $assets->links() }}
        </div>
    </div>
</div>
@endsection
