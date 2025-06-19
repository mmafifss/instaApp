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

        if (like::where('user_id', $post->id)->exists()) {
            return response()->json(['message' => 'Already liked'], 400);
        }

        like::create([
            'user_id' => $post->user_id,
            'post_id' => $post->id
        ]);

        return response()->json(['message' => $post->id]);
    }

    public function unlike(Post $post)
    {
        $user = Auth::user();

        $like = like::where('user_id', $user->id)->first();

        if (! $like) {
            return response()->json(['message' => 'Not liked yet'], 400);
        }

        $like->delete();

        return response()->json(['message' => 'Post unliked']);
    }
}
