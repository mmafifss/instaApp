<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user', 'likes', 'comments')->latest()->get();
        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'caption' => 'required_without:image|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $post = new Post();
        $post->user_id = auth()->id();
        $post->caption = $request->caption;
    
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $post->image_path = $path;
        }
    
        $post->save();
    
        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post->load('user')
        ], 201);
    }

    public function show(Post $post)
    {
        $post->load('user', 'likes', 'comments');
        return response()->json($post);
    }

    public function update(Request $request, Post $post)
    {
        if (Gate::denies('update-post', $post)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'caption' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($post->image_path);
            $path = $request->file('image')->store('posts', 'public');
            $post->image_path = $path;
        }

        if ($request->has('caption')) {
            $post->caption = $request->caption;
        }

        $post->save();

        return response()->json($post);
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Unauthorized action'
            ], 403);
        }
    
        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }
    
        $post->delete();
    
        return response()->json([
            'message' => 'Post deleted successfully'
        ]);
    }
}
