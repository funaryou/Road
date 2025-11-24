<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ProfileController extends Controller
{
    public function profile(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $posts = $user->posts()->with('files','likes')->get();
        
        // Get posts that user has liked
        $likedPosts = $user->likes()->with('files')->get();

        // Get places that user has liked
        $likedPlaces = $user->placeLikes()->get();
        
        // Calculate stats
        $followingCount = $user->following()->count();
        $likeCount = $posts->sum(function($post) {
            return $post->likes->count();
        });
        
        return view('app.profile.index', compact('user', 'posts', 'likedPosts', 'likedPlaces', 'followingCount', 'likeCount'));
    }
}
