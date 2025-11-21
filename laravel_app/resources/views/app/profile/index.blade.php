<x-layout>
    <div>
        <h1>Profile</h1>
        <p>{{ $user->name }}</p>
        <img src="{{ asset('storage/' . $user->icon) }}" alt="">
        <div>
            @foreach ($posts as $post)
                <div>
                    <a href="{{ route('post.show', ['id' => $post->id]) }}">
                        <img src="{{ asset('storage/' . $post->files->first()->path) }}" alt="">
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</x-layout>
