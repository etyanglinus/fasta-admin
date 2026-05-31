<?php

namespace App\Http\Controllers\Admin\Cms;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::with('category');
        if ($search = $request->get('search')) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('slug', 'like', "%{$search}%")
                ->orWhere('author', 'like', "%{$search}%");
        }
        if ($category = $request->get('category_id')) {
            $query->where('category_id', $category);
        }

        $posts = $query->latest()->paginate(15);
        $categories = BlogCategory::where('status', 1)->get();

        return view('admin-views.cms.blog-posts.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = BlogCategory::where('status', 1)->get();

        return view('admin-views.cms.blog-posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_posts,slug',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|image|max:5120',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'author' => 'nullable|string|max:255',
            'status' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->only([
            'category_id',
            'title',
            'excerpt',
            'content',
            'meta_title',
            'meta_description',
            'author',
            'published_at',
        ]);
        $data['slug'] = Str::slug($request->slug);
        $data['status'] = $request->boolean('status');
        $data['views'] = 0;

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = Helpers::upload('cms/blog/', $request->file('featured_image')->getClientOriginalExtension(), $request->file('featured_image'));
        }

        BlogPost::create($data);

        Toastr::success('Blog post created successfully.');

        return redirect()->route('admin.business-settings.cms.blog-posts.index');
    }

    public function edit(BlogPost $blogPost)
    {
        $categories = BlogCategory::where('status', 1)->get();

        return view('admin-views.cms.blog-posts.edit', compact('blogPost', 'categories'));
    }

    public function update(Request $request, BlogPost $blogPost)
    {
        $request->validate([
            'category_id' => 'required|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'slug' => "required|string|max:255|unique:blog_posts,slug,{$blogPost->id}",
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|image|max:5120',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'author' => 'nullable|string|max:255',
            'status' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->only([
            'category_id',
            'title',
            'excerpt',
            'content',
            'meta_title',
            'meta_description',
            'author',
            'published_at',
        ]);
        $data['slug'] = Str::slug($request->slug);
        $data['status'] = $request->boolean('status');

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = Helpers::update('cms/blog/', $blogPost->featured_image, $request->file('featured_image')->getClientOriginalExtension(), $request->file('featured_image'));
        }

        $blogPost->update($data);

        Toastr::success('Blog post updated successfully.');

        return redirect()->route('admin.business-settings.cms.blog-posts.index');
    }

    public function destroy(BlogPost $blogPost)
    {
        $blogPost->delete();
        Toastr::success('Blog post removed successfully.');

        return back();
    }

    public function status(BlogPost $blogPost, $status)
    {
        $blogPost->update(['status' => (bool) $status]);

        return response()->json(['status' => 'success']);
    }
}
