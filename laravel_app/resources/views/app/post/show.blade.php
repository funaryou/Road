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
    </div>
</x-layout>
