<x-layout title=Posts>
    <p><a href="{{ route('post.form') }}">新規投稿</a></p>
    @if($posts->isEmpty())
        <p>投稿はありません。</p>
    @else
        @foreach($posts as $post)
            <div>
                <a href="{{ route('post.show', $post->id) }}">
                    <img src="{{ asset('storage/' . $post->user->icon) }}" alt="User Icon">
                </a>
            </div>
        @endforeach
    @endif
</x-layout>
