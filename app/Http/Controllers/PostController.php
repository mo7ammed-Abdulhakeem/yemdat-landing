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

        // Dynamically calculate the Top 5 most used tags across all published posts
        $allTags = Post::where('is_published', true)->whereNotNull('tags')->pluck('tags')->flatten()->map(function ($tag) {
            return trim(is_string($tag) ? trim(json_decode($tag, true) ?? $tag, '"') : $tag);
        })->filter()->toArray();

        $tagCounts = array_count_values($allTags);
        arsort($tagCounts);
        $top5Tags = array_slice(array_keys($tagCounts), 0, 5);

        // Core categories hardcoded + Top 5 dynamic tags
        $coreTypes = ['announcement', 'update', 'article'];
        $presetCategories = array_merge(['All'], $coreTypes, $top5Tags, ['Other']);

        // Unified Category/Tag Filtering
        if ($request->filled('category') && $request->category !== 'All') {
            $category = $request->category;

            if ($category === 'Other') {
                // If "Other", exclude the core types AND exclude any post that has ONE of the top 5 tags
                $query->whereNotIn('type', $coreTypes);
                foreach ($top5Tags as $topTag) {
                    $query->whereJsonDoesntContain('tags', $topTag);
                }
            }
            else {
                // Check if it's a primary 'type' or a JSON 'tag'
                $query->where(function ($q) use ($category) {
                    $q->where('type', $category)
                        ->orWhereJsonContains('tags', $category);
                });
            }
        }

        // Determine if we should show the Hero section
        // Hero is only shown on Page 1, without active search/category filters
        $showHero = !$request->filled('search') && (!$request->filled('category') || $request->category === 'All') && $request->get('page', 1) == 1;

        $heroPost = null;
        if ($showHero) {
            // Priority: is_featured = true, fallback to latest post
            $heroPost = (clone $query)->where('is_featured', true)->latest()->first();
            if (!$heroPost) {
                $heroPost = (clone $query)->latest()->first();
            }

            if ($heroPost) {
                $query->where('id', '!=', $heroPost->id);
            }
        }

        $posts = $query->latest()->paginate(10)->withQueryString();

        $activeCategory = $request->get('category', 'All');
        $searchQuery = $request->get('search', '');

        return view('news.index', compact('posts', 'heroPost', 'showHero', 'presetCategories', 'activeCategory', 'searchQuery', 'top5Tags'));
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
