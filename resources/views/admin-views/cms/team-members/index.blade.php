@extends('layouts.admin.app')

@section('title', 'Team Members')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <h1 class="page-header-title">Team Members</h1>
        <a href="{{ route('admin.business-settings.cms.team-members.create') }}" class="btn btn--primary">Add Member</a>
    </div>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                <thead class="thead-light">
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $member)
                    <tr>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->role }}</td>
                        <td>{{ $member->status ? 'Active' : 'Inactive' }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.business-settings.cms.team-members.edit', $member) }}" class="btn btn-sm btn--primary">Edit</a>
                            <form action="{{ route('admin.business-settings.cms.team-members.destroy', $member) }}" method="post" class="d-inline-block">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-sm btn--danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $members->links() }}
        </div>
    </div>
</div>
@endsection
