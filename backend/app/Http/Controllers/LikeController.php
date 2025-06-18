<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function like(Post $post)
    {
        $user = Auth::user();

        if ($post->likes()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Already liked'], 400);
        }

        $post->likes()->create([
            'user_id' => $user->id,
        ]);

        return response()->json(['message' => 'Post liked']);
    }

    public function unlike(Post $post)
    {
        $user = Auth::user();

        $like = $post->likes()->where('user_id', $user->id)->first();

        if (! $like) {
            return response()->json(['message' => 'Not liked yet'], 400);
        }

        $like->delete();

        return response()->json(['message' => 'Post unliked']);
    }
}
