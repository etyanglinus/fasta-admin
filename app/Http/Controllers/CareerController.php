<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobOpening;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class CareerController extends Controller
{
    public function index()
    {
        if (!Schema::hasTable('job_openings')) {
            $jobs = new LengthAwarePaginator([], 0, 10);

            return view('careers.index', compact('jobs'));
        }

        $jobs = JobOpening::where('status', 1)
            ->where(function ($query) {
                $query->whereNull('closing_date')
                    ->orWhere('closing_date', '>=', now());
            })
            ->latest('created_at')
            ->paginate(10);

        return view('careers.index', compact('jobs'));
    }

    public function show($id)
    {
        abort_if(!Schema::hasTable('job_openings'), 404);

        $job = JobOpening::where('status', 1)
            ->where(function ($query) {
                $query->whereNull('closing_date')
                    ->orWhere('closing_date', '>=', now());
            })
            ->findOrFail($id);

        return view('careers.show', compact('job'));
    }

    public function apply(Request $request, $id)
    {
        abort_if(!Schema::hasTable('job_openings') || !Schema::hasTable('job_applications'), 404);

        $job = JobOpening::findOrFail($id);

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'resume' => 'nullable|mimes:pdf,doc,docx|max:10240',
            'cover_letter' => 'nullable|string',
        ]);

        $data = $request->only(['full_name', 'email', 'phone', 'cover_letter']);
        if ($request->hasFile('resume')) {
            $data['resume'] = $request->file('resume')->store('career/resumes', 'public');
        }

        $job->applications()->create($data);

        return back()->with('success', 'Your application has been submitted.');
    }
}
