<x-layout title="Travel Schedule">
    <div class="relative w-full h-screen overflow-hidden">
        <!-- Google Map (Full Screen Background) -->
        <div id="map" class="absolute inset-0 w-full h-full"></div>

        <!-- Back Button (Floating) -->
        <div class="absolute top-16 left-5 z-20">
            <a href="{{ route('tour.select') }}" class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-lg">
                <span class="material-icons text-gray-800">arrow_back_ios</span>
            </a>
        </div>

        <!-- Pull-up Sheet -->
        <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-[30px] shadow-[0_-4px_20px_rgba(0,0,0,0.1)] z-10" style="max-height: 70vh; overflow-y: auto;">
            <!-- Handle Bar -->
            <div class="flex justify-center pt-3 pb-2">
                <div class="w-10 h-1 bg-gray-300 rounded-full"></div>
            </div>

            <!-- Content -->
            <div class="px-5 pb-24">
                <!-- Title and Edit Icon -->
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-3xl font-bold">{{ $tour->name }}</h1>
                    <button class="p-2">
                        <span class="material-icons text-gray-600">edit</span>
                    </button>
                </div>

                <!-- Day Tabs -->
                <div class="flex gap-2 overflow-x-auto mb-6 pb-1">
                    @for($i = 1; $i <= $tour->days; $i++)
                        <button class="day-tab whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium transition-all {{ $i === 1 ? 'bg-black text-white' : 'bg-gray-100 text-gray-800' }}"
                                data-day="{{ $i }}"
                                onclick="switchDay({{ $i }})">
                            {{ $i }}day
                        </button>
                    @endfor
                </div>

                <!-- Schedule List -->
                <div id="schedule-list">
                    @php
                        $waypointsByDay = $tour->waypoints->groupBy('day_number');
                    @endphp

                    @for($day = 1; $day <= $tour->days; $day++)
                        @php
                            $waypoints = $waypointsByDay->get($day, collect());
                        @endphp
                        
                        <div class="day-content {{ $day !== 1 ? 'hidden' : '' }}" data-day="{{ $day }}">
                            <div class="flex items-center justify-between mb-4">
                                <div class="text-lg font-bold">{{ $day }}day</div>
                                <a href="{{ route('waypoint.form', ['id' => $tour->id, 'day' => $day]) }}" class="flex items-center text-gray-400 text-sm add-place-link" data-day="{{ $day }}">
                                    <span class="material-icons text-lg mr-1">add</span>
                                    <span>Add place</span>
                                </a>
                            </div>

                            @if($waypoints->isEmpty())
                                <div class="text-center py-8 text-gray-400">
                                    <p>No places added yet</p>
                                </div>
                            @else
                                @foreach($waypoints as $index => $waypoint)
                                    @php
                                        $isLast = $loop->last;
                                        $iconColor = '#ffc107';
                                        $iconName = 'landscape';
                                    @endphp

                                    <div class="flex mb-6 relative">
                                        <!-- Icon and Line Container -->
                                        <div class="w-[30px] flex flex-col items-center relative pt-1.5 z-[2]">
                                            <span class="material-icons w-5 h-5 rounded-full text-white text-sm flex justify-center items-center z-[3]" 
                                                  style="background-color: {{ $iconColor }};">
                                                {{ $iconName }}
                                            </span>
                                            @if(!$isLast)
                                                <div class="w-0.5 absolute top-[25px] bottom-[-15px] z-[1]" style="background-color: {{ $iconColor }};"></div>
                                            @endif
                                        </div>

                                        <!-- Details Box -->
                                        <div class="flex-grow bg-[#f2f2f7] rounded-[10px] p-4 ml-2.5">
                                            <div class="flex justify-between items-start mb-1">
                                                <h3 class="text-base font-bold m-0 leading-tight">{{ $loop->iteration }}. {{ $waypoint->place->name }}</h3>
                                            </div>

                                            @if($waypoint->place->rating)
                                                <p class="text-sm text-[#333] my-1">Rating: ★{{ $waypoint->place->rating }}</p>
                                            @endif

                                            @if($waypoint->place->image_url)
                                                <div class="flex gap-1.5 mt-2.5">
                                                    <div class="w-20 h-12 rounded-md bg-cover bg-center" style="background-image: url('{{ $waypoint->place->image_url }}');"></div>
                                                </div>
                                            @endif

                                            @if(!$isLast)
                                                <div class="flex items-center mt-4 text-sm text-[#8e8e93]">
                                                    <span class="material-icons text-base mr-1.5">drive_eta</span>
                                                    <span>Transportation info</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    <x-navigation active-page="plan" />

    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.googleMaps.api_key') }}&callback=initMap" async defer></script>
    <script>
        function initMap() {
            const waypoints = @json($tour->waypoints);
            const defaultLocation = { lat: 35.1815, lng: 136.9066 }; // Nagoya
            
            let center = defaultLocation;
            if (waypoints.length > 0) {
                center = { lat: parseFloat(waypoints[0].place.lat), lng: parseFloat(waypoints[0].place.lng) };
            }

            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 12,
                center: center,
                disableDefaultUI: true, // Clean map without controls
                zoomControl: false,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: false,
            });

            const bounds = new google.maps.LatLngBounds();

            waypoints.forEach((wp, index) => {
                const position = { lat: parseFloat(wp.place.lat), lng: parseFloat(wp.place.lng) };
                
                const marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    title: wp.place.name,
                    label: {
                        text: (index + 1).toString(),
                        color: 'white',
                        fontSize: '14px',
                        fontWeight: 'bold'
                    },
                });

                const infoWindow = new google.maps.InfoWindow({
                    content: `<div><strong>${wp.place.name}</strong><br>Day ${wp.day_number}</div>`
                });

                marker.addListener("click", () => {
                    infoWindow.open(map, marker);
                });

                bounds.extend(position);
            });

            if (waypoints.length > 0) {
                map.fitBounds(bounds);
            }
        }

        function switchDay(day) {
            // Update tab styles
            document.querySelectorAll('.day-tab').forEach(tab => {
                if (parseInt(tab.dataset.day) === day) {
                    tab.classList.remove('bg-gray-100', 'text-gray-800');
                    tab.classList.add('bg-black', 'text-white');
                } else {
                    tab.classList.remove('bg-black', 'text-white');
                    tab.classList.add('bg-gray-100', 'text-gray-800');
                }
            });

            // Show/hide day content
            document.querySelectorAll('.day-content').forEach(content => {
                if (parseInt(content.dataset.day) === day) {
                    content.classList.remove('hidden');
                } else {
                    content.classList.add('hidden');
                }
            });
        }

        // Check URL parameter and switch to that day on page load
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const dayParam = urlParams.get('day');
            if (dayParam) {
                const day = parseInt(dayParam);
                switchDay(day);
            }
        });
    </script>
</x-layout>