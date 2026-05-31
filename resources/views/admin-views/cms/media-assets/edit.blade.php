@extends('layouts.admin.app')

@section('title', 'Edit Media Asset')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <h1 class="page-header-title">Edit Media Asset</h1>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.business-settings.cms.media-assets.update', $mediaAsset) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $mediaAsset->title) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-control">
                            <option value="">Select type</option>
                            @foreach(['image','video','document'] as $type)
                                <option value="{{ $type }}" {{ old('type', $mediaAsset->type) === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">File</label>
                        <input type="file" name="file" class="form-control">
                        @if($mediaAsset->file)
                            <p class="mt-2"><a href="{{ asset('storage/'.$mediaAsset->file) }}" target="_blank">Current file</a></p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="form-check mt-4 pt-2">
                            <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', $mediaAsset->status) ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">Publish</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn--primary">Update Asset</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
