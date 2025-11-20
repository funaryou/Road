<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>ツアー作成</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        label { display: block; margin-top: 10px; }
        input, textarea { width: 300px; padding: 5px; margin-top: 5px; }
        button { margin-top: 15px; padding: 5px 10px; }
    </style>
</head>
<body>
    <h1>ツアー作成</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('tour.store') }}">
        @csrf
        <div>
            <label>タイトル<br>
                <input type="text" name="title" value="{{ old('title') }}">
            </label>
        </div>
        <div>
            <label>内容<br>
                <textarea name="content" rows="6">{{ old('content') }}</textarea>
            </label>
        </div>
        <div>
            <button type="submit">作成</button>
        </div>
    </form>

    <p><a href="{{ route('tour.select') }}">一覧に戻る</a></p>
</body>
</html>