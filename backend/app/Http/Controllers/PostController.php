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
        $request->validate([
            'image' => 'required|image|max:2048',
            'caption' => 'nullable|string|max:1000',
        ]);

        $path = $request->file('image')->store('posts', 'public');

        $post = Post::create([
            'user_id' => Auth::id(),
            'image_path' => $path,
            'caption' => $request->caption,
        ]);

        return response()->json($post, 201);
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
        if (Gate::denies('delete-post', $post)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        Storage::disk('public')->delete($post->image_path);
        $post->delete();

        return response()->json(['message' => 'Post deleted']);
    }
}
