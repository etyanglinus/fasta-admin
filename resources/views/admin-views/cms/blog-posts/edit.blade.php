@extends('layouts.admin.app')

@section('title', 'Edit Blog Post')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <h1 class="page-header-title">Edit Blog Post</h1>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.business-settings.cms.blog-posts.update', $blogPost) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $blogPost->title) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">Select category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $blogPost->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Short Description</label>
                        <textarea name="short_description" class="form-control" rows="3">{{ old('short_description', $blogPost->short_description) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Body</label>
                        <textarea name="content" class="form-control" rows="8">{{ old('content', $blogPost->content) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Featured Image</label>
                        <input type="file" name="featured_image" class="form-control">
                        @if($blogPost->featured_image)
                            <img src="{{ asset('storage/'.$blogPost->featured_image) }}" class="img-fluid mt-2" style="max-height:120px;">
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Publish Date</label>
                        <input type="date" name="published_at" class="form-control" value="{{ old('published_at', optional($blogPost->published_at)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">SEO Title</label>
                        <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $blogPost->meta_title) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">SEO Description</label>
                        <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description', $blogPost->meta_description) }}">
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', $blogPost->status) ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">Publish</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn--primary">Update Post</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
