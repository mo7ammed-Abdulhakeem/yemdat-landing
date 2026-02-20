<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasPermission('posts')) {
            abort(403);
        }

        $posts = Post::with('author')->latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        if (!auth()->user()->hasPermission('posts')) {
            abort(403);
        }

        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('posts')) {
            abort(403);
        }

        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'content_en' => 'required|string',
            'content_ar' => 'required|string',
            'type' => 'required|in:announcement,update,article',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'tags' => 'nullable|string', // Will be received as JSON string or comma separated
        ]);

        $slug = Str::slug($validated['title_en']);
        if (Post::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }
        $validated['slug'] = $slug;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        // Parse tags if they exist (assuming a simple comma-separated input for now)
        if (!empty($validated['tags'])) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        }
        else {
            $validated['tags'] = null;
        }

        $validated['created_by'] = auth()->id();
        $validated['is_published'] = $request->has('is_published');

        Post::create($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        if (!auth()->user()->hasPermission('posts')) {
            abort(403);
        }

        // Only author or super admin can edit
        if ($post->created_by !== auth()->id() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized action. You can only edit your own posts.');
        }

        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if (!auth()->user()->hasPermission('posts')) {
            abort(403);
        }

        // Only author or super admin can edit
        if ($post->created_by !== auth()->id() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized action. You can only update your own posts.');
        }

        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'content_en' => 'required|string',
            'content_ar' => 'required|string',
            'type' => 'required|in:announcement,update,article',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'tags' => 'nullable|string',
        ]);

        if ($post->title_en !== $validated['title_en']) {
            $slug = Str::slug($validated['title_en']);
            if (Post::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
                $slug = $slug . '-' . time();
            }
            $validated['slug'] = $slug;
        }

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        // Handle Tags
        if (!empty($validated['tags'])) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        }
        else {
            $validated['tags'] = null;
        }

        $validated['is_published'] = $request->has('is_published');

        $post->update($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        if (!auth()->user()->hasPermission('posts')) {
            abort(403);
        }

        // Only author or super admin can delete
        if ($post->created_by !== auth()->id() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized action. You can only delete your own posts.');
        }

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Post deleted successfully.');
    }

    public function toggle(Post $post)
    {
        if (!auth()->user()->hasPermission('posts')) {
            abort(403);
        }

        // Only author or super admin can toggle
        if ($post->created_by !== auth()->id() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $post->update(['is_published' => !$post->is_published]);
        return back()->with('success', 'Post status updated.');
    }
}
