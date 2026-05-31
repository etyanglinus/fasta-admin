@extends('layouts.admin.app')

@section('title', 'Edit Page')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <h1 class="page-header-title">Edit Page</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.business-settings.cms.pages.update', $page) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $page->title) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug', $page->slug) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Page Type</label>
                        <select name="page_type" class="form-control" required>
                            @foreach(['custom','about-us','careers','press-media','blog','faq','sustainability','vendor-stories'] as $type)
                                <option value="{{ $type }}" {{ old('page_type', $page->page_type) === $type ? 'selected' : '' }}>{{ ucfirst(str_replace('-', ' ', $type)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Short Description</label>
                        <textarea name="short_description" class="form-control" rows="3">{{ old('short_description', $page->short_description) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Content</label>
                        <textarea name="content" class="form-control" rows="8">{{ old('content', $page->content) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Featured Image</label>
                        <input type="file" name="featured_image" class="form-control">
                        @if($page->featured_image)
                            <img src="{{ asset('storage/'.$page->featured_image) }}" class="img-fluid mt-2" style="max-height:120px;">
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Banner Image</label>
                        <input type="file" name="banner_image" class="form-control">
                        @if($page->banner_image)
                            <img src="{{ asset('storage/'.$page->banner_image) }}" class="img-fluid mt-2" style="max-height:120px;">
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">SEO Title</label>
                        <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $page->meta_title) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">SEO Description</label>
                        <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description', $page->meta_description) }}">
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', $page->status) ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">Publish</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn--primary">Update Page</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
