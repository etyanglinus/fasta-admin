@extends('layouts.admin.app')

@section('title', 'Edit Blog Category')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <h1 class="page-header-title">Edit Blog Category</h1>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.business-settings.cms.blog-categories.update', $blogCategory) }}" method="post">
                @csrf
                @method('put')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $blogCategory->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug', $blogCategory->slug) }}" required>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', $blogCategory->status) ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">Active</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn--primary">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
