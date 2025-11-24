<x-layout title="My Plan">
    <div class="relative w-full h-screen bg-cover bg-center bg-no-repeat overflow-hidden" 
         style="background-image: url('{{ asset('assets/images/plan-background.png') }}');">
        
        <header class="pt-12 pb-6">
            <h1 class="text-3xl font-bold ml-5">My plan</h1>
        </header>
        
        <!-- Pull-up sheet -->
        <main class="main-content bg-white rounded-t-[30px] shadow-[0_-4px_10px_rgba(0,0,0,0.05)] flex-grow p-5 pt-8 relative">
            <!-- Handle bar -->
            <div class="absolute top-2.5 left-1/2 -translate-x-1/2 w-15 h-1.5 bg-gray-300 rounded"></div>
            
            <!-- Plan Grid -->
            <div class="plan-grid grid grid-cols-2 gap-4 pt-4 w-full pb-[120px]">
                <!-- Create Plan Button -->
                <div class="plan-item create-plan-item h-[150px] rounded-[15px] flex items-center justify-center overflow-hidden bg-[#f0f8ff] shadow-[0_4px_10px_rgba(173,216,230,0.4)]">
                    <a href="{{ route('tour.form') }}" class="w-full h-full flex flex-col items-center justify-center p-5 no-underline">
                        <div class="w-12 h-12 bg-[#2B9BFF] rounded-lg flex items-center justify-center mb-2 relative">
                            <span class="text-white text-3xl font-light">+</span>
                        </div>
                        <h2 class="text-base font-semibold bg-[#2B9BFF] rounded-lg text-white py-1 px-2.5 m-0">Create plan</h2>
                    </a>
                </div>

                <!-- Saved Plans -->
                @foreach($tours as $tour)
                    <div class="plan-item saved-plan-item h-[150px] rounded-[15px] bg-white shadow-[0_4px_10px_rgba(0,0,0,0.1)] flex flex-col p-0 items-start justify-start overflow-hidden cursor-pointer">
                        <a href="{{ route('tour.show', ['id' => $tour->id]) }}" class="w-full h-full flex flex-col no-underline">
                            @if($tour->topImage)
                                <img src="{{ $tour->topImage }}" alt="{{ $tour->name }}" class="w-full h-[60%] object-cover">
                            @else
                                <div class="w-full h-[60%] bg-gray-200 flex items-center justify-center text-gray-400 text-sm">No Image</div>
                            @endif
                            <div class="p-2.5 w-full box-border">
                                <h2 class="text-sm font-semibold m-0 text-[#333] whitespace-nowrap overflow-hidden text-ellipsis">{{ $tour->name }}</h2>
                                <p class="text-xs text-[#8e8e93] mt-1 mb-0">{{ $tour->days }} days / {{ $tour->waypointsCount }} places</p>
                            </div>
                        </a>
                    </div>
                @endforeach

                <!-- Empty slots (dashed border) -->
                @php
                    $totalSlots = 6;
                    $filledSlots = count($tours) + 1; // +1 for create button
                    $emptySlots = max(0, $totalSlots - $filledSlots);
                @endphp
                
                @for($i = 0; $i < $emptySlots; $i++)
                    <div class="plan-item dashed-border-item h-[150px] rounded-[15px] bg-transparent border-2 border-dashed border-[#cccccc]"></div>
                @endfor
            </div>
        </main>
    </div>

    <x-navigation active-page="plan" />

    <style>
        .main-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            max-height: 70vh;
            overflow-y: auto;
        }
    </style>
</x-layout>