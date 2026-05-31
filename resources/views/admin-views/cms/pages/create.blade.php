@extends('layouts.admin.app')

@section('title', 'Create Page')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <h1 class="page-header-title">Create Page</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.business-settings.cms.pages.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Page Type</label>
                        <select name="page_type" class="form-control" required>
                            <option value="custom">Custom</option>
                            <option value="about-us">About Us</option>
                            <option value="careers">Careers</option>
                            <option value="press-media">Press & Media</option>
                            <option value="blog">Blog</option>
                            <option value="faq">FAQ</option>
                            <option value="sustainability">Sustainability</option>
                            <option value="vendor-stories">Vendor Stories</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Short Description</label>
                        <textarea name="short_description" class="form-control" rows="3">{{ old('short_description') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Content</label>
                        <textarea name="content" class="form-control" rows="8">{{ old('content') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Featured Image</label>
                        <input type="file" name="featured_image" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Banner Image</label>
                        <input type="file" name="banner_image" class="form-control">
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
                    <button type="submit" class="btn btn--primary">Save Page</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
