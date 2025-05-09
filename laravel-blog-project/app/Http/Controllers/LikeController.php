<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Like or unlike a post
     */
    public function toggleLike(Post $post)
    {
        // Check if user already liked the post
        $existing_like = Like::where('post_id', $post->id)
            ->where('user_id', Auth::id())
            ->first();
            
        if ($existing_like) {
            // User already liked the post, so unlike it
            $existing_like->delete();
            return response()->json([
                'liked' => false,
                'count' => $post->likesCount()
            ]);
        } else {
            // User has not liked the post, so like it
            Like::create([
                'post_id' => $post->id,
                'user_id' => Auth::id()
            ]);
            return response()->json([
                'liked' => true,
                'count' => $post->likesCount()
            ]);
        }
    }
}
