<?php

namespace App\Http\Controllers\Admin\Cms;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function index(Request $request)
    {
        $query = TeamMember::query();
        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%")->orWhere('position', 'like', "%{$search}%");
        }

        $members = $query->orderBy('display_order')->paginate(15);

        return view('admin-views.cms.team-members.index', compact('members'));
    }

    public function create()
    {
        return view('admin-views.cms.team-members.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:5120',
            'bio' => 'nullable|string',
            'linkedin_url' => 'nullable|url|max:255',
            'display_order' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
        ]);

        $data = $request->only(['name', 'position', 'bio', 'linkedin_url', 'display_order']);
        $data['status'] = $request->boolean('status');

        if ($request->hasFile('photo')) {
            $data['photo'] = Helpers::upload('cms/team-members/', $request->file('photo')->getClientOriginalExtension(), $request->file('photo'));
        }

        TeamMember::create($data);

        Toastr::success('Team member added successfully.');

        return redirect()->route('admin.business-settings.cms.team-members.index');
    }

    public function edit(TeamMember $teamMember)
    {
        return view('admin-views.cms.team-members.edit', compact('teamMember'));
    }

    public function update(Request $request, TeamMember $teamMember)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:5120',
            'bio' => 'nullable|string',
            'linkedin_url' => 'nullable|url|max:255',
            'display_order' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
        ]);

        $data = $request->only(['name', 'position', 'bio', 'linkedin_url', 'display_order']);
        $data['status'] = $request->boolean('status');

        if ($request->hasFile('photo')) {
            $data['photo'] = Helpers::update('cms/team-members/', $teamMember->photo, $request->file('photo')->getClientOriginalExtension(), $request->file('photo'));
        }

        $teamMember->update($data);

        Toastr::success('Team member updated successfully.');

        return redirect()->route('admin.business-settings.cms.team-members.index');
    }

    public function destroy(TeamMember $teamMember)
    {
        $teamMember->delete();
        Toastr::success('Team member removed successfully.');

        return back();
    }
}
