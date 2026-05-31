@extends('layouts.admin.app')

@section('title', 'Create Blog Post')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <h1 class="page-header-title">Create Blog Post</h1>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.business-settings.cms.blog-posts.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">Select category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Short Description</label>
                        <textarea name="short_description" class="form-control" rows="3">{{ old('short_description') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Body</label>
                        <textarea name="content" class="form-control" rows="8">{{ old('content') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Featured Image</label>
                        <input type="file" name="featured_image" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Publish Date</label>
                        <input type="date" name="published_at" class="form-control" value="{{ old('published_at') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">SEO Title</label>
                        <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">SEO Description</label>
                        <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description') }}">
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status" id="status" value="1" checked>
                            <label class="form-check-label" for="status">Publish</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn--primary">Save Post</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
