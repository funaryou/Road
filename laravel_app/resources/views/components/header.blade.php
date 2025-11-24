<header class="bg-[#024887] text-white border-2 rounded-b-[20px] sticky top-0 z-[102]">
    @if ($type === 'search-bar')
        <div>
            <div class="flex justify-between items-center w-full max-w-[400px] mx-auto p-2.5">
                <span class="font-black text-2xl px-2.5">LOGO</span>
            </div>
        </div>

        <div class="bg-white rounded-[10px] mx-5 mb-2.5 p-2.5 flex items-center">
            <img src="{{ asset('assets/images/search.svg') }}" class="w-4 mx-2.5" alt="glass">
            <input type="text" placeholder="What do you look for?" class="border-none outline-none flex-grow text-xs font-bold text-black">
        </div>
    @elseif ($type === 'search-bar-and-back')
        <div>
            <div class="flex items-center mx-5 my-2.5">
                <a href="{{ url()->previous() }}">
                    <img src="{{ asset('assets/images/back_button.png') }}" class="mr-2.5 ml-[15px]" alt="back_button">
                </a>
                <div class="flex items-center bg-white rounded-[10px] py-2.5 w-full h-[42px]">
                    <span class="mx-2.5 pt-[5px]"><img src="{{ asset('assets/images/search.png') }}" alt="search_icon"></span>
                    <input type="text" placeholder="Nagoya Food" class="border-none outline-none flex-grow text-xs font-bold text-[#c6c6c6] bg-transparent">
                </div>
            </div>
        </div>
    @elseif ($type === 'back')
        <div class="flex items-center px-5 py-4">
            <a href="{{ route('post.index') }}" class="flex items-center text-white no-underline">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                <span class="text-xl font-bold">{{ $title }}</span>
            </a>
        </div>
    @endif
</header>
