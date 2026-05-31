@extends('layouts.admin.app')

@section('title', 'Edit Press Release')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <h1 class="page-header-title">Edit Press Release</h1>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.business-settings.cms.press-releases.update', $pressRelease) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $pressRelease->title) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date</label>
                        <input type="date" name="publish_date" class="form-control" value="{{ old('publish_date', optional($pressRelease->publish_date)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug', $pressRelease->slug) }}" placeholder="Auto-generated from title if blank">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Summary</label>
                        <textarea name="summary" class="form-control" rows="3">{{ old('summary', $pressRelease->summary) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Content</label>
                        <textarea name="content" class="form-control" rows="7">{{ old('content', $pressRelease->content) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Featured Image</label>
                        <input type="file" name="featured_image" class="form-control" accept="image/*">
                        @if($pressRelease->featured_image)
                            <img src="{{ asset('storage/'.$pressRelease->featured_image) }}" class="img-fluid mt-2" style="max-height:120px;">
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">PDF File</label>
                        <input type="file" name="pdf_file" class="form-control" accept="application/pdf">
                        @if($pressRelease->pdf_file)
                            <a href="{{ asset('storage/'.$pressRelease->pdf_file) }}" target="_blank" class="d-block mt-2">View current PDF</a>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="form-check mt-4 pt-2">
                            <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', $pressRelease->status) ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">Publish</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn--primary">Update Release</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
