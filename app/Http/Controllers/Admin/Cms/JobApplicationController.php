<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = JobApplication::with('jobOpening');
        if ($search = $request->get('search')) {
            $query->where('full_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%");
        }

        $applications = $query->latest()->paginate(15);

        return view('admin-views.cms.job-applications.index', compact('applications'));
    }

    public function show(JobApplication $jobApplication)
    {
        return view('admin-views.cms.job-applications.show', compact('jobApplication'));
    }

    public function destroy(JobApplication $jobApplication)
    {
        $jobApplication->delete();
        Toastr::success('Job application deleted.');

        return back();
    }

    public function status(JobApplication $jobApplication, Request $request)
    {
        $request->validate(['status' => 'required|string|max:50']);
        $jobApplication->update(['status' => $request->status]);

        return back();
    }
}
