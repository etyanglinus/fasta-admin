@extends('layouts.admin.app')

@section('title', 'Edit Job Opening')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <h1 class="page-header-title">Edit Job Opening</h1>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.business-settings.cms.job-openings.update', $jobOpening) }}" method="post">
                @csrf
                @method('put')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $jobOpening->title) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" value="{{ old('location', $jobOpening->location) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Department</label>
                        <input type="text" name="department" class="form-control" value="{{ old('department', $jobOpening->department) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Experience</label>
                        <input type="text" name="experience" class="form-control" value="{{ old('experience', $jobOpening->experience) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="5">{{ old('description', $jobOpening->description) }}</textarea>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', $jobOpening->status) ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">Open</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn--primary">Update Opening</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
