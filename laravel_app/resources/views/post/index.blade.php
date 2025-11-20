<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>投稿一覧</title>
</head>
<body>
    <h1>投稿一覧</h1>

    <p><a href="{{ route('post.form') }}">新規投稿</a></p>

    @if($posts->isEmpty())
        <p>投稿はありません。</p>
    @else
        <ul>
            @foreach($posts as $post)
                <li style="margin-bottom:1.5rem;">
                    <div><strong>{{ optional($post->user)->name ?? 'ゲスト' }}</strong> - <small>{{ $post->created_at }}</small></div>
                    <div style="white-space:pre-wrap;">{{ $post->content }}</div>
                    @if($post->files && $post->files->isNotEmpty())
                        <div>添付ファイル:
                            <ul>
                                @foreach($post->files as $file)
                                    <li><a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" rel="noopener">{{ basename($file->file_path) }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</body>
</html>
