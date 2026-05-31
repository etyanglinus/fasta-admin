<?php

namespace App\Http\Controllers\Admin\Cms;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Page;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $query = Page::query();
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('page_type', 'like', "%{$search}%");
            });
        }

        $pages = $query->latest()->paginate(15);

        return view('admin-views.cms.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin-views.cms.pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug',
            'page_type' => 'required|string|max:100',
            'short_description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|max:5120',
            'banner_image' => 'nullable|image|max:5120',
            'status' => 'nullable|boolean',
        ]);

        $slug = Str::slug($request->slug);

        $data = $request->only([
            'title',
            'page_type',
            'short_description',
            'content',
            'meta_title',
            'meta_description',
        ]);
        $data['slug'] = $slug;
        $data['status'] = $request->boolean('status');

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = Helpers::upload('cms/pages/featured/', $request->file('featured_image')->getClientOriginalExtension(), $request->file('featured_image'));
        }
        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = Helpers::upload('cms/pages/banner/', $request->file('banner_image')->getClientOriginalExtension(), $request->file('banner_image'));
        }

        Page::create($data);

        Toastr::success('Page created successfully.');

        return redirect()->route('admin.business-settings.cms.pages.index');
    }

    public function edit(Page $page)
    {
        return view('admin-views.cms.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => "required|string|max:255|unique:pages,slug,{$page->id}",
            'page_type' => 'required|string|max:100',
            'short_description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|max:5120',
            'banner_image' => 'nullable|image|max:5120',
            'status' => 'nullable|boolean',
        ]);

        $slug = Str::slug($request->slug);

        $data = $request->only([
            'title',
            'page_type',
            'short_description',
            'content',
            'meta_title',
            'meta_description',
        ]);
        $data['slug'] = $slug;
        $data['status'] = $request->boolean('status');

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = Helpers::update('cms/pages/featured/', $page->featured_image, $request->file('featured_image')->getClientOriginalExtension(), $request->file('featured_image'));
        }
        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = Helpers::update('cms/pages/banner/', $page->banner_image, $request->file('banner_image')->getClientOriginalExtension(), $request->file('banner_image'));
        }

        $page->update($data);

        Toastr::success('Page updated successfully.');

        return redirect()->route('admin.business-settings.cms.pages.index');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        Toastr::success('Page removed successfully.');

        return back();
    }

    public function status(Page $page, $status)
    {
        $page->update(['status' => (bool) $status]);

        return response()->json(['status' => 'success']);
    }
}
