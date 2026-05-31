@extends('layouts.admin.app')

@section('title', 'Add Media Asset')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <h1 class="page-header-title">Add Media Asset</h1>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.business-settings.cms.media-assets.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-control">
                            <option value="">Select type</option>
                            <option value="image">Image</option>
                            <option value="video">Video</option>
                            <option value="document">Document</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">File</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check mt-4 pt-2">
                            <input class="form-check-input" type="checkbox" name="status" id="status" value="1" checked>
                            <label class="form-check-label" for="status">Publish</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn--primary">Save Asset</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
