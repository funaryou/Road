<x-layout title=Post>
    <form method="POST" action="{{ route('post.store') }}" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="content">本文</label>
            
            <textarea id="content" name="content" rows="6" cols="60"></textarea>
            <label for="files">画像アップロード</label><br>
            <input type="file" name="files[]" id="files" multiple>

            <button type="submit">投稿する</button>
        </div>
    </form>
    @if ($errors->any())
        <div class="text-red-500">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <p><a href="{{ route('post.index') }}">投稿一覧へ</a></p>
</x-layout>