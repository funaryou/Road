<div class="fixed bottom-[30px] left-1/2 transform -translate-x-1/2 z-[1000]">
    <nav class="flex flex-row px-6 py-2.5 rounded-[50px] bg-[#024887] shadow-[0_4px_12px_rgba(0,0,0,0.5)] w-[380px] justify-between items-center">
        
        {{-- HOME --}}
        <a href="{{ route('post.index') }}" 
           class="flex items-center justify-center border-none cursor-pointer rounded-[40px] transition-all duration-300 h-10 box-border whitespace-nowrap overflow-hidden
                  {{ $activePage === 'home' ? 'bg-white text-[#024887] px-5 w-auto min-w-[100px] shadow-md' : 'bg-transparent text-white w-10 px-2.5 min-w-[40px] hover:brightness-125' }}">
            <span class="material-icons text-[30px] transition-colors duration-300 {{ $activePage === 'home' ? 'text-[#024887]' : 'text-white' }}">home</span>
            <span class="text-base font-bold ml-2 transition-all duration-300 {{ $activePage === 'home' ? 'opacity-100 w-auto' : 'opacity-0 w-0 ml-0' }}">HOME</span>
        </a>

        {{-- MAP --}}
        <a href="{{ route('index') }}" 
           class="flex items-center justify-center border-none cursor-pointer rounded-[40px] transition-all duration-300 h-10 box-border whitespace-nowrap overflow-hidden
                  {{ $activePage === 'map' ? 'bg-white text-[#024887] px-5 w-auto min-w-[100px] shadow-md' : 'bg-transparent text-white w-10 px-2.5 min-w-[40px] hover:brightness-125' }}">
            <span class="material-icons text-[30px] transition-colors duration-300 {{ $activePage === 'map' ? 'text-[#024887]' : 'text-white' }}">place</span>
            <span class="text-base font-bold ml-2 transition-all duration-300 {{ $activePage === 'map' ? 'opacity-100 w-auto' : 'opacity-0 w-0 ml-0' }}">MAP</span>
        </a>

        {{-- PLAN --}}
        <a href="{{ route('tour.select') }}" 
           class="flex items-center justify-center border-none cursor-pointer rounded-[40px] transition-all duration-300 h-10 box-border whitespace-nowrap overflow-hidden
                  {{ $activePage === 'plan' ? 'bg-white text-[#024887] px-5 w-auto min-w-[100px] shadow-md' : 'bg-transparent text-white w-10 px-2.5 min-w-[40px] hover:brightness-125' }}">
            <span class="material-icons text-[30px] transition-colors duration-300 {{ $activePage === 'plan' ? 'text-[#024887]' : 'text-white' }}">flight_takeoff</span>
            <span class="text-base font-bold ml-2 transition-all duration-300 {{ $activePage === 'plan' ? 'opacity-100 w-auto' : 'opacity-0 w-0 ml-0' }}">PLAN</span>
        </a>

        {{-- PROFILE --}}
        <a href="{{ route('profile', ['id' => Auth::id()]) }}" 
           class="flex items-center justify-center border-none cursor-pointer rounded-[40px] transition-all duration-300 h-10 box-border whitespace-nowrap overflow-hidden
                  {{ $activePage === 'profile' ? 'bg-white text-[#024887] px-5 w-auto min-w-[100px] shadow-md' : 'bg-transparent text-white w-10 px-2.5 min-w-[40px] hover:brightness-125' }}">
            <span class="material-icons text-[30px] transition-colors duration-300 {{ $activePage === 'profile' ? 'text-[#024887]' : 'text-white' }}">person</span>
            <span class="text-base font-bold ml-2 transition-all duration-300 {{ $activePage === 'profile' ? 'opacity-100 w-auto' : 'opacity-0 w-0 ml-0' }}">Profile</span>
        </a>

    </nav>
</div>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">