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

        // Get related posts (same type, excluding current)
        $relatedPosts = Post::where('is_published', true)
            ->where('type', $post->type)
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(3)
            ->get();

        return view('news.show', compact('post', 'relatedPosts'));
    }
}
