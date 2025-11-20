<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>投稿フォーム</title>
</head>
<body>
    <h1>投稿ページ</h1>

    @if(isset($user) && $user)
        <p>ログインユーザー: {{ $user->name ?? 'ユーザー' }}</p>
    @else
        <p>未ログインユーザー</p>
    @endif

    <form method="POST" action="{{ route('post.store') }}" enctype="multipart/form-data">
        @csrf

        <div>
            <label for="company_id">会社（任意）</label>
            <select name="company_id" id="company_id">
                <option value="">選択しない</option>
                @foreach($companies ?? [] as $company)
                    <option value="{{ $company->id }}">{{ $company->name ?? $company->title ?? '会社' }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="content">本文</label><br>
            <textarea id="content" name="content" rows="6" cols="60">{{ old('content') }}</textarea>
            @error('content')
                <div style="color:red">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <button type="submit">投稿する</button>
        </div>
    </form>

    <p><a href="{{ route('post.index') }}">投稿一覧へ</a></p>
</body>
</html>