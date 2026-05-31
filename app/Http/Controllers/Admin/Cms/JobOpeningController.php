<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\JobOpening;
use App\Models\JobApplication;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class JobOpeningController extends Controller
{
    public function index(Request $request)
    {
        $query = JobOpening::query();
        if ($search = $request->get('search')) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('department', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
        }

        $openings = $query->latest()->paginate(15);

        return view('admin-views.cms.job-openings.index', compact('openings'));
    }

    public function create()
    {
        return view('admin-views.cms.job-openings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'employment_type' => 'nullable|string|max:255',
            'salary_range' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'closing_date' => 'nullable|date',
            'status' => 'nullable|boolean',
        ]);

        $data = $request->only(['title','department','location','employment_type','salary_range','description','requirements','responsibilities','closing_date']);
        $data['status'] = $request->boolean('status');

        JobOpening::create($data);

        Toastr::success('Job opening has been created.');

        return redirect()->route('admin.business-settings.cms.job-openings.index');
    }

    public function edit(JobOpening $jobOpening)
    {
        return view('admin-views.cms.job-openings.edit', compact('jobOpening'));
    }

    public function update(Request $request, JobOpening $jobOpening)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'employment_type' => 'nullable|string|max:255',
            'salary_range' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'closing_date' => 'nullable|date',
            'status' => 'nullable|boolean',
        ]);

        $data = $request->only(['title','department','location','employment_type','salary_range','description','requirements','responsibilities','closing_date']);
        $data['status'] = $request->boolean('status');

        $jobOpening->update($data);

        Toastr::success('Job opening updated.');

        return redirect()->route('admin.business-settings.cms.job-openings.index');
    }

    public function destroy(JobOpening $jobOpening)
    {
        $jobOpening->delete();
        Toastr::success('Job opening removed.');

        return back();
    }

    public function status(JobOpening $jobOpening, $status)
    {
        $jobOpening->update(['status' => (bool) $status]);

        return response()->json(['status' => 'success']);
    }

    public function applications(JobOpening $jobOpening)
    {
        $applications = $jobOpening->applications()->latest()->paginate(15);

        return view('admin-views.cms.job-openings.applications', compact('jobOpening', 'applications'));
    }
}
