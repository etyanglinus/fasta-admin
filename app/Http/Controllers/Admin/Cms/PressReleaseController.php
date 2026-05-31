<?php

namespace App\Http\Controllers\Admin\Cms;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\PressRelease;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PressReleaseController extends Controller
{
    public function index(Request $request)
    {
        $query = PressRelease::query();
        if ($search = $request->get('search')) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('slug', 'like', "%{$search}%")
                ->orWhere('summary', 'like', "%{$search}%");
        }

        $pressReleases = $query->latest()->paginate(15);

        return view('admin-views.cms.press-releases.index', compact('pressReleases'));
    }

    public function create()
    {
        return view('admin-views.cms.press-releases.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:press_releases,slug',
            'summary' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'pdf_file' => 'nullable|mimes:pdf|max:10240',
            'featured_image' => 'nullable|image|max:5120',
            'publish_date' => 'nullable|date',
            'status' => 'nullable|boolean',
        ]);

        $data = $request->only(['title','summary','content','publish_date']);
        $data['slug'] = $this->uniqueSlug($request->slug ?: $request->title);
        $data['status'] = $request->boolean('status');

        if ($request->hasFile('pdf_file')) {
            $data['pdf_file'] = Helpers::upload('cms/press/pdf/', $request->file('pdf_file')->getClientOriginalExtension(), $request->file('pdf_file'));
        }
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = Helpers::upload('cms/press/images/', $request->file('featured_image')->getClientOriginalExtension(), $request->file('featured_image'));
        }

        PressRelease::create($data);

        Toastr::success('Press release created successfully.');

        return redirect()->route('admin.business-settings.cms.press-releases.index');
    }

    public function edit(PressRelease $pressRelease)
    {
        return view('admin-views.cms.press-releases.edit', compact('pressRelease'));
    }

    public function update(Request $request, PressRelease $pressRelease)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => "nullable|string|max:255|unique:press_releases,slug,{$pressRelease->id}",
            'summary' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'pdf_file' => 'nullable|mimes:pdf|max:10240',
            'featured_image' => 'nullable|image|max:5120',
            'publish_date' => 'nullable|date',
            'status' => 'nullable|boolean',
        ]);

        $data = $request->only(['title','summary','content','publish_date']);
        $data['slug'] = $this->uniqueSlug($request->slug ?: $request->title, $pressRelease->id);
        $data['status'] = $request->boolean('status');

        if ($request->hasFile('pdf_file')) {
            $data['pdf_file'] = Helpers::update('cms/press/pdf/', $pressRelease->pdf_file, $request->file('pdf_file')->getClientOriginalExtension(), $request->file('pdf_file'));
        }
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = Helpers::update('cms/press/images/', $pressRelease->featured_image, $request->file('featured_image')->getClientOriginalExtension(), $request->file('featured_image'));
        }

        $pressRelease->update($data);

        Toastr::success('Press release updated successfully.');

        return redirect()->route('admin.business-settings.cms.press-releases.index');
    }

    public function destroy(PressRelease $pressRelease)
    {
        $pressRelease->delete();

        Toastr::success('Press release deleted.');

        return back();
    }

    public function status(PressRelease $pressRelease, $status)
    {
        $pressRelease->update(['status' => (bool) $status]);

        return response()->json(['status' => 'success']);
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: Str::random(8);
        $slug = $base;
        $count = 1;

        while (PressRelease::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . $count++;
        }

        return $slug;
    }
}
