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

        // Optional filtering by type
        if ($request->has('type') && in_array($request->type, ['announcement', 'update', 'article'])) {
            $query->where('type', $request->type);
        }

        // Optional filtering by tags
        if ($request->has('tag')) {
            $tag = $request->tag;
            // JSON matching for the array of tags
            $query->whereJsonContains('tags', $tag);
        }

        $posts = $query->latest()->paginate(12);

        return view('news.index', compact('posts'));
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
