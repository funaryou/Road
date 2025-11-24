<x-layout title=Posts>
    <x-header type="search-bar" title="Loog" />
    <main class="max-w-[600px] mx-5 pb-[120px] flex flex-col items-center">
        @if($posts->isEmpty())
            <p class="mt-4 text-gray-500">投稿はありません。</p>
        @else
            @foreach($posts as $post)
                <article class="bg-white rounded-[10px] mt-[17px] p-[10px] shadow-sm relative overflow-hidden w-[360px] h-[300px]">
                    <div class="flex items-center px-[15px] py-[10px]">
                        <a href="{{ route('post.show', $post->id) }}">
                            <img src="{{ asset('storage/' . $post->user->icon) }}" class="w-10 h-10 rounded-full mr-[10px] object-cover" alt="profile_image">
                        </a>
                        <span class="font-bold text-[#333]">{{ $post->user->name }}</span>
                    </div>
                    
                    <a href="{{ route('post.show', $post->id) }}">
                        @if($post->files->isNotEmpty())
                            <img src="{{ asset('storage/' . $post->files->first()->file_path) }}" class="w-[320px] h-[180px] block ml-[10px] rounded-[10px] object-cover" alt="post_image">
                        @else
                            <div class="w-[320px] h-[180px] block ml-[10px] rounded-[10px] bg-gray-200 flex items-center justify-center text-gray-400">No Image</div>
                        @endif
                    </a>

                    <div class="flex px-[15px] py-[10px] items-center text-[#333] text-base">
                        <div class="flex items-center mr-5 font-bold">
                            <img src="{{ asset('assets/images/heart.svg') }}" class="w-[25px] mr-[10px]" alt="heart">
                            <span>{{ $post->likes->count() }}</span>
                        </div>
                        <div class="flex items-center mr-5 font-bold">
                            <img src="{{ asset('assets/images/message.svg') }}" class="w-[25px] mr-[10px]" alt="message">
                            <span>{{ $post->comments->count() }}</span>
                        </div>
                    </div>
                </article>
            @endforeach
        @endif
        
        <div class="fixed bottom-[100px] right-[calc(50%-180px)] z-[999]">
            <a href="{{ route('post.form') }}" class="bg-[#2D9CDB] text-white w-14 h-14 rounded-full flex items-center justify-center shadow-lg hover:bg-[#2680b3] transition-colors">
                <span class="text-xl font-bold">Post</span>
            </a>
        </div>
        
        <div style="height: 150px;"></div>
    </main>
    <x-navigation active-page="home" />
</x-layout>
