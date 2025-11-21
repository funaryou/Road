<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ProfileController extends Controller
{
    public function profile(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $posts = $user->posts()->with('post.files')->get();
        return view('app.profile.index', compact('user', 'posts'));
    }
}
