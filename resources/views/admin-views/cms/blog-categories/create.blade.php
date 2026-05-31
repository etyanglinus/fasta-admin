@extends('layouts.admin.app')

@section('title', 'Create Blog Category')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <h1 class="page-header-title">Create Blog Category</h1>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.business-settings.cms.blog-categories.store') }}" method="post">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" required>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status" id="status" value="1" checked>
                            <label class="form-check-label" for="status">Active</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn--primary">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
