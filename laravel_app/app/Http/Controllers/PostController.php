<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostFile;
use App\Http\Requests\PostStoreRequest;

class PostController extends Controller
{
    public function postForm()
    {
        return view('app.post.form');
    }

    public function postStore(PostStoreRequest $request)
    {

        $user = $request->user();
        
        // 投稿作成
        $post = $user->posts()->create([
            'content' => $request->input('content'),
        ]);
        
        // ファイルがあれば保存して PostFile を作成
        if ($request->hasFile('files')) {
            $now = now();
            $files = collect($request->file('files'))->map(function ($file) use ($post, $now) {
                return [
                    'post_id' => $post->id,
                    'file_path' => $file->store('posts', 'public'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            })->toArray();

            PostFile::insert($files);
        }

        return redirect()->route('post.index');
    }

    public function posts()
    {
        $posts = Post::with(['user', 'files'])->orderBy('created_at', 'desc')->get();
        return view('app.post.index', [
            'posts' => $posts,
        ]);
    }
    
    public function postShow($id)
    {
        $post = Post::with(['user', 'files'])
            ->withCount('likes')
            ->findOrFail($id);
        return view('app.post.show', ['post' => $post]);
    }

    public function postLike($id)
    {
        $post = Post::findOrFail($id);
        if ($post->likes()->where('user_id', auth()->id())->exists()) {
            $post->likes()->detach(auth()->id());
        } else {
            $post->likes()->attach(auth()->id());
        }
        return redirect()->back();
    }
    
}
