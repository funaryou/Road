<x-layout title="Post">
    <x-header type="back" title="" />
    
    <div class="pb-[120px]">
        <!-- Image Carousel Section -->
        <div class="relative w-full aspect-square bg-gray-100 overflow-hidden" id="carousel-container">
            @if($post->files->isNotEmpty())
                <div class="carousel-wrapper flex transition-transform duration-300 ease-out h-full" id="carousel-wrapper">
                    @foreach($post->files as $index => $file)
                        <img src="{{ asset('storage/' . $file->file_path) }}" 
                             class="w-full h-full object-cover flex-shrink-0" 
                             alt="post_image_{{ $index + 1 }}">
                    @endforeach
                </div>
                
                <!-- Image counter -->
                <div class="absolute top-4 right-4 bg-black/50 text-white px-3 py-1 rounded-full text-sm" id="image-counter">
                    1/{{ $post->files->count() }}
                </div>
                
                <!-- Navigation arrows (optional, for desktop) -->
                @if($post->files->count() > 1)
                    <button onclick="previousImage()" class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/30 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-black/50 transition">
                        ‹
                    </button>
                    <button onclick="nextImage()" class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/30 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-black/50 transition">
                        ›
                    </button>
                @endif
                
                <!-- Dots indicator -->
                <div class="absolute -bottom-8 left-0 right-0 flex justify-center gap-2" id="dots-container">
                    @foreach($post->files as $index => $file)
                        <div class="dot w-2 h-2 rounded-full transition-colors {{ $index === 0 ? 'bg-[#007AFF]' : 'bg-gray-300' }}" 
                             data-index="{{ $index }}"></div>
                    @endforeach
                </div>

                <script>
                    let currentIndex = 0;
                    const totalImages = {{ $post->files->count() }};
                    const wrapper = document.getElementById('carousel-wrapper');
                    const counter = document.getElementById('image-counter');
                    const dots = document.querySelectorAll('.dot');
                    
                    function updateCarousel() {
                        wrapper.style.transform = `translateX(-${currentIndex * 100}%)`;
                        counter.textContent = `${currentIndex + 1}/${totalImages}`;
                        
                        // Update dots
                        dots.forEach((dot, index) => {
                            if (index === currentIndex) {
                                dot.classList.remove('bg-gray-300');
                                dot.classList.add('bg-[#007AFF]');
                            } else {
                                dot.classList.remove('bg-[#007AFF]');
                                dot.classList.add('bg-gray-300');
                            }
                        });
                    }
                    
                    function nextImage() {
                        if (currentIndex < totalImages - 1) {
                            currentIndex++;
                            updateCarousel();
                        }
                    }
                    
                    function previousImage() {
                        if (currentIndex > 0) {
                            currentIndex--;
                            updateCarousel();
                        }
                    }
                    
                    // Touch swipe support
                    let touchStartX = 0;
                    let touchEndX = 0;
                    const container = document.getElementById('carousel-container');
                    
                    container.addEventListener('touchstart', (e) => {
                        touchStartX = e.changedTouches[0].screenX;
                    });
                    
                    container.addEventListener('touchend', (e) => {
                        touchEndX = e.changedTouches[0].screenX;
                        handleSwipe();
                    });
                    
                    function handleSwipe() {
                        const swipeThreshold = 50;
                        if (touchStartX - touchEndX > swipeThreshold) {
                            nextImage();
                        } else if (touchEndX - touchStartX > swipeThreshold) {
                            previousImage();
                        }
                    }
                </script>
            @else
                <div class="w-full h-full flex items-center justify-center text-gray-400">No Image</div>
            @endif
        </div>

        <!-- Post Info Section -->
        <div class="px-5 mt-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('storage/' . $post->user->icon) }}" class="w-10 h-10 rounded-full object-cover border border-gray-200" alt="user_icon">
                    <span class="font-bold text-sm">{{ $post->user->name }}</span>
                </div>
                <div class="flex items-center gap-4">
                    <form action="{{ route('post.like', $post->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="focus:outline-none">
                            @if($post->likes->contains(Auth::user()))
                                <img src="{{ asset('assets/images/heart-full.svg') }}" class="w-7 h-7" alt="liked">
                            @else
                                <img src="{{ asset('assets/images/heart.svg') }}" class="w-7 h-7" alt="like">
                            @endif
                        </button>
                    </form>
                    <button class="focus:outline-none" onclick="document.getElementById('comment-input').focus()">
                        <img src="{{ asset('assets/images/message.svg') }}" class="w-7 h-7" alt="comment">
                    </button>
                </div>
            </div>

            <div class="text-sm leading-relaxed text-[#333] mb-4">
                {{ $post->content }}
            </div>

            <!-- Comment Input -->
            <form action="{{ route('post.comment.store', $post->id) }}" method="POST" class="flex items-center gap-2 border-t pt-4">
                @csrf
                <input type="text" id="comment-input" name="content" placeholder="Add a comment..." class="flex-grow bg-gray-100 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#024887]">
                <button type="submit" class="text-[#024887] font-bold text-sm px-2">Post</button>
            </form>
            </div>
        <!-- Comments List -->
        @if($post->comments->isNotEmpty())
            <div class="px-5 mt-4 space-y-4">
                @foreach($post->comments as $comment)
                    <div class="flex items-start gap-3">
                        <img src="{{ asset('storage/' . $comment->user->icon) }}" class="w-8 h-8 rounded-full object-cover" alt="comment_user_icon">
                        <div>
                            <span class="font-bold text-sm">{{ $comment->user->name }}</span>
                            <p class="text-sm text-[#333]">{{ $comment->content }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

        <x-navigation active-page="home" />
</x-layout>
