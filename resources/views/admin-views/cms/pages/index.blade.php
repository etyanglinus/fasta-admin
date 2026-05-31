@extends('layouts.admin.app')

@section('title', 'Pages')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <h1 class="page-header-title">Pages</h1>
        <a href="{{ route('admin.business-settings.cms.pages.create') }}" class="btn btn--primary">Create Page</a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Updated</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pages as $page)
                        <tr>
                            <td>{{ $page->title }}</td>
                            <td>{{ $page->slug }}</td>
                            <td>{{ $page->page_type }}</td>
                            <td>{{ $page->status ? 'Published' : 'Draft' }}</td>
                            <td>{{ $page->updated_at->format('Y-m-d') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.business-settings.cms.pages.edit', $page) }}" class="btn btn-sm btn--primary">Edit</a>
                                <form action="{{ route('admin.business-settings.cms.pages.destroy', $page) }}" method="post" class="d-inline-block">
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
        </div>
        <div class="card-footer">
            {{ $pages->links() }}
        </div>
    </div>
</div>
@endsection
