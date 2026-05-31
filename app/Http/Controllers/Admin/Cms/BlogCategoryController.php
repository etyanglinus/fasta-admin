<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogCategory::query();
        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%")->orWhere('slug', 'like', "%{$search}%");
        }
        $categories = $query->latest()->paginate(15);

        return view('admin-views.cms.blog-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin-views.cms.blog-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_categories,slug',
            'status' => 'nullable|boolean',
        ]);

        BlogCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'status' => $request->boolean('status'),
        ]);

        Toastr::success('Blog category created successfully.');

        return redirect()->route('admin.business-settings.cms.blog-categories.index');
    }

    public function edit(BlogCategory $blogCategory)
    {
        return view('admin-views.cms.blog-categories.edit', compact('blogCategory'));
    }

    public function update(Request $request, BlogCategory $blogCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => "required|string|max:255|unique:blog_categories,slug,{$blogCategory->id}",
            'status' => 'nullable|boolean',
        ]);

        $blogCategory->update([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'status' => $request->boolean('status'),
        ]);

        Toastr::success('Blog category updated successfully.');

        return redirect()->route('admin.business-settings.cms.blog-categories.index');
    }

    public function destroy(BlogCategory $blogCategory)
    {
        $blogCategory->delete();
        Toastr::success('Blog category removed successfully.');

        return back();
    }
}
