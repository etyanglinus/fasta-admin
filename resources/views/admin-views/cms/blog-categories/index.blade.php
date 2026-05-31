@extends('layouts.admin.app')

@section('title', 'Blog Categories')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <h1 class="page-header-title">Blog Categories</h1>
        <a href="{{ route('admin.business-settings.cms.blog-categories.create') }}" class="btn btn--primary">Create Category</a>
    </div>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                <thead class="thead-light">
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>{{ $category->status ? 'Active' : 'Inactive' }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.business-settings.cms.blog-categories.edit', $category) }}" class="btn btn-sm btn--primary">Edit</a>
                            <form action="{{ route('admin.business-settings.cms.blog-categories.destroy', $category) }}" method="post" class="d-inline-block">
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
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection
