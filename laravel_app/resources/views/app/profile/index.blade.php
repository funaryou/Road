<x-layout title="Profile">
    <div class="min-h-screen bg-white pb-24">
        <!-- Header -->
        <div class="flex justify-end p-4">
            <button class="p-2">
                <span class="material-icons text-2xl">menu</span>
            </button>
        </div>

        <!-- Profile Section -->
        <div class="flex flex-col items-center px-6 pb-6">
            <!-- Avatar with Edit Icon -->
            <div class="relative mb-4">
                @if(Auth::id() === $user->id)
                    <a href="{{ route('profile.edit') }}">
                        <img src="{{ asset('storage/' . $user->icon) }}" 
                             alt="{{ $user->name }}" 
                             class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg cursor-pointer">
                    </a>
                    <a href="{{ route('profile.edit') }}" class="absolute bottom-0 right-0 w-10 h-10 bg-[#007aff] rounded-full flex items-center justify-center border-4 border-white">
                        <span class="material-icons text-white text-lg">edit</span>
                    </a>
                @else
                    <img src="{{ asset('storage/' . $user->icon) }}" 
                         alt="{{ $user->name }}" 
                         class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">
                @endif
            </div>

            <!-- Username -->
            <h1 class="text-xl font-bold mb-4">{{ $user->name }}</h1>

            <!-- Stats -->
            <div class="flex gap-12 mb-6">
                <div class="text-center">
                    <div class="text-2xl font-bold">{{ $followingCount ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Following</div>
                </div>
                <div class="w-px bg-gray-300"></div>
                <div class="text-center">
                    <div class="text-2xl font-bold">{{ $likeCount ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Like</div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex border-b border-gray-200">
            <button class="tab-button flex-1 py-3 text-center font-semibold border-b-2 border-black" data-tab="posts" onclick="switchTab('posts')">
                Posts
            </button>
            <button class="tab-button flex-1 py-3 text-center font-semibold text-gray-400 border-b-2 border-transparent" data-tab="likes" onclick="switchTab('likes')">
                Likes
            </button>
            <button class="tab-button flex-1 py-3 text-center font-semibold text-gray-400 border-b-2 border-transparent" data-tab="places" onclick="switchTab('places')">
                Places
            </button>
        </div>

        <!-- Posts Grid -->
        <div id="posts-grid" class="tab-content grid grid-cols-3 gap-1 p-1">
            @foreach ($posts as $post)
                <a href="{{ route('post.show', ['id' => $post->id]) }}" class="aspect-square">
                    <img src="{{ asset('storage/' . $post->files->first()->file_path) }}" 
                         alt="" 
                         class="w-full h-full object-cover">
                </a>
            @endforeach
        </div>

        <!-- Liked Posts Grid -->
        <div id="likes-grid" class="tab-content hidden grid grid-cols-3 gap-1 p-1">
            @foreach ($likedPosts as $post)
                <a href="{{ route('post.show', ['id' => $post->id]) }}" class="aspect-square">
                    <img src="{{ asset('storage/' . $post->files->first()->file_path) }}" 
                         alt="" 
                         class="w-full h-full object-cover">
                </a>
            @endforeach
        </div>

        <!-- Liked Places Grid -->
        <div id="places-grid" class="tab-content hidden grid grid-cols-3 gap-1 p-1">
            @foreach ($likedPlaces as $place)
                <a href="{{ route('index', ['place_id' => $place->google_place_id]) }}" class="aspect-square relative group block">
                    @if($place->image_url)
                        <img src="{{ $place->image_url }}" 
                             alt="{{ $place->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400 text-xs">No Image</div>
                    @endif
                    <div class="absolute bottom-0 left-0 right-0 bg-black/50 p-1">
                        <p class="text-white text-[10px] truncate">{{ $place->name }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <x-navigation active-page="profile" />

    <script>
        function switchTab(tab) {
            // Update tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                if (button.dataset.tab === tab) {
                    button.classList.remove('text-gray-400', 'border-transparent');
                    button.classList.add('border-black');
                } else {
                    button.classList.add('text-gray-400', 'border-transparent');
                    button.classList.remove('border-black');
                }
            });

            // Update content visibility
            document.querySelectorAll('.tab-content').forEach(content => {
                if (content.id === tab + '-grid') {
                    content.classList.remove('hidden');
                } else {
                    content.classList.add('hidden');
                }
            });
        }
    </script>
</x-layout>
