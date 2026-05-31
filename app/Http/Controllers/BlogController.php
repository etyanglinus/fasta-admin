<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        if (!Schema::hasTable('blog_posts') || !Schema::hasTable('blog_categories')) {
            $featured = null;
            $latest = collect();
            $categories = collect();

            return view('blog.index', compact('featured', 'latest', 'categories'));
        }

        $query = BlogPost::with('category')->where('status', 1);

        if ($request->filled('category')) {
            $category = BlogCategory::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $featured = (clone $query)->whereNotNull('featured_image')->latest('published_at')->first();
        $latest = $query->latest('published_at')->paginate(9);
        $categories = BlogCategory::where('status', 1)->orderBy('name')->get();

        return view('blog.index', compact('featured', 'latest', 'categories'));
    }

    public function show($slug)
    {
        abort_if(!Schema::hasTable('blog_posts'), 404);

        $post = BlogPost::with('category')->where('slug', $slug)->where('status', 1)->firstOrFail();
        $post->increment('views');

        $related = BlogPost::where('status', 1)
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        $readingTime = max(1, (int) ceil(str_word_count(strip_tags($post->content)) / 200));

        return view('blog.show', compact('post', 'related', 'readingTime'));
    }
}
