@extends('layouts.admin.app')

@section('title', 'Create Press Release')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <h1 class="page-header-title">Create Press Release</h1>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.business-settings.cms.press-releases.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date</label>
                        <input type="date" name="publish_date" class="form-control" value="{{ old('publish_date') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" placeholder="Auto-generated from title if blank">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Summary</label>
                        <textarea name="summary" class="form-control" rows="3">{{ old('summary') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Content</label>
                        <textarea name="content" class="form-control" rows="7">{{ old('content') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Featured Image</label>
                        <input type="file" name="featured_image" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">PDF File</label>
                        <input type="file" name="pdf_file" class="form-control" accept="application/pdf">
                    </div>
                    <div class="col-md-6">
                        <div class="form-check mt-4 pt-2">
                            <input class="form-check-input" type="checkbox" name="status" id="status" value="1" checked>
                            <label class="form-check-label" for="status">Publish</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn--primary">Save Release</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
