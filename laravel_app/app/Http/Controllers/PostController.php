<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Company;
use App\Models\PostFile;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
            public function postForm()
    {
        // 投稿フォーム表示
        $user = request()->user();
        // 未ログイン時に company_id を選べるよう会社一覧を渡す
        $companies = Company::all();
        return view('post.form', [
            'user' => $user,
            'companies' => $companies,
        ]);
    }

        public function postStore(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'files.*' => 'nullable|file|max:10240',
        ]);

        $user = $request->user();
        $userId = $user ? $user->id : 0;

        // 投稿作成
        $post = Post::create([
            'user_id' => $userId,
            'content' => $validated['content'],
        ]);

        // ファイルがあれば保存して PostFile を作成
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                if (!$file->isValid()) {
                    continue;
                }
                $path = $file->store('posts', 'public');
                PostFile::create([
                    'post_id' => $post->id,
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()->route('post.index');
    }

        public function posts()
    {
        $posts = Post::with(['user', 'files'])->orderBy('created_at', 'desc')->get();
        return view('post.index', [
            'posts' => $posts,
        ]);
    }
}
