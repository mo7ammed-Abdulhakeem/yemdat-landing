<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of posts/news.
     */
    public function index(Request $request)
    {
        $query = Post::where('is_published', true)->with('author');

        // Text Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title_en', 'LIKE', "%{$search}%")
                    ->orWhere('title_ar', 'LIKE', "%{$search}%")
                    ->orWhere('content_en', 'LIKE', "%{$search}%")
                    ->orWhere('content_ar', 'LIKE', "%{$search}%");
            });
        }

        // Unified Category/Tag Filtering
        if ($request->filled('category') && $request->category !== 'All') {
            $category = $request->category;
            // Check if it's a primary 'type' or a JSON 'tag'
            $query->where(function ($q) use ($category) {
                $q->where('type', $category)
                    ->orWhereJsonContains('tags', $category);
            });
        }

        // Determine if we should show the Hero section
        // Hero is only shown on Page 1, without active search/category filters
        $showHero = !$request->filled('search') && (!$request->filled('category') || $request->category === 'All') && $request->get('page', 1) == 1;

        $heroPost = null;
        if ($showHero) {
            $heroPost = (clone $query)->latest()->first();
            if ($heroPost) {
                $query->where('id', '!=', $heroPost->id);
            }
        }

        $posts = $query->latest()->paginate(10)->withQueryString();

        // Pass available preset categories to the view
        $presetCategories = ['All', 'Announcement', 'Community', 'Data Analysis', 'Security', 'Automation'];
        $activeCategory = $request->get('category', 'All');
        $searchQuery = $request->get('search', '');

        return view('news.index', compact('posts', 'heroPost', 'showHero', 'presetCategories', 'activeCategory', 'searchQuery'));
    }

    /**
     * Display the specified post.
     */
    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            ->where('is_published', true)
            ->with('author')
            ->firstOrFail();

        // Get related posts (matching tags, exclude current)
        $tags = is_string($post->tags) ? json_decode($post->tags, true) : $post->tags;

        $relatedPosts = Post::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->where(function ($query) use ($tags, $post) {
            if (is_array($tags) && count($tags) > 0) {
                foreach ($tags as $tag) {
                    $query->orWhereJsonContains('tags', $tag);
                }
            }
            else {
                $query->where('type', $post->type);
            }
        })
            ->latest()
            ->take(3)
            ->get();

        // If we didn't get enough related posts by tag, backfill with same type
        if ($relatedPosts->count() < 3) {
            $excludeIds = $relatedPosts->pluck('id')->push($post->id)->toArray();

            $morePosts = Post::where('is_published', true)
                ->whereNotIn('id', $excludeIds)
                ->where('type', $post->type)
                ->latest()
                ->take(3 - $relatedPosts->count())
                ->get();

            $relatedPosts = $relatedPosts->merge($morePosts);
        }

        return view('news.show', compact('post', 'relatedPosts'));
    }
}
