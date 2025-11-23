<x-layout>
    <div>
        @if($post->files->isNotEmpty())
        <div>
            <img src="{{ asset('storage/' . $post->files->first()->file_path) }}" alt="">
        </div>
        @endif
        <div><img src="{{ asset('storage/' . $post->user->icon) }}" alt=""></div>
        <div>{{ $post->user->name }}</div>
        <div>{{ $post->content }}</div>
        <div>{{ $post->likes()->count() }}</div>
        <form action="{{ route('post.like', $post->id) }}" method="POST">
            @csrf
            <button type="submit">いいね</button>
        </form>
        <form action="{{ route('post.comment.store', $post->id) }}" method="POST">
            @csrf
            <input type="text" name="content">
            <button type="submit">コメント</button>
            @error('content')
                <div>{{ $message }}</div>
            @enderror
        </form>
    </div>
    <div>
        @foreach($post->comments as $comment)
            <div>
                <img src="{{ asset('storage/' . $comment->user->icon) }}" alt="">
                <div>{{ $comment->user->name }}</div>
                <div>{{ $comment->content }}</div>
            </div>
        @endforeach
    </div>
</x-layout>
