@extends('layouts.admin.app')

@section('title', 'Blog Posts')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <h1 class="page-header-title">Blog Posts</h1>
        <a href="{{ route('admin.business-settings.cms.blog-posts.create') }}" class="btn btn--primary">Create Post</a>
    </div>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                <thead class="thead-light">
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Updated</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $post)
                    <tr>
                        <td>{{ $post->title }}</td>
                        <td>{{ optional($post->category)->name }}</td>
                        <td>{{ $post->status ? 'Published' : 'Draft' }}</td>
                        <td>{{ $post->updated_at->format('Y-m-d') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.business-settings.cms.blog-posts.edit', $post) }}" class="btn btn-sm btn--primary">Edit</a>
                            <form action="{{ route('admin.business-settings.cms.blog-posts.destroy', $post) }}" method="post" class="d-inline-block">
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
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection
