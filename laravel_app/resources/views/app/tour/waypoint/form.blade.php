<x-layout title="Add Places">
    <div class="flex flex-col h-screen">
        <!-- Header (Fixed) -->
        <header class="absolute top-0 left-0 right-0 z-30 bg-transparent pt-4 px-5">
            <a href="{{ route('tour.show', $tour->id) }}" class="inline-block">
                <span class="material-icons text-white text-2xl drop-shadow-lg">arrow_back_ios</span>
            </a>
        </header>

        <!-- Map Section (Top 30%) -->
        <div id="map" class="w-full h-[30vh] relative flex-shrink-0"></div>

        <!-- Content Section (Bottom 70%) -->
        <div class="flex-1 bg-white flex flex-col">
            <!-- Search Bar -->
            <div class="px-5 pt-4 pb-3">
                <div class="flex items-center bg-gray-100 rounded-full px-4 py-2">
                    <span class="material-icons text-gray-400 mr-2">search</span>
                    <input type="text" 
                           id="place-search-input"
                           placeholder="Places/attraction/hotel" 
                           class="flex-grow border-none outline-none text-sm bg-transparent">
                </div>
            </div>

            <!-- Day Selector -->
            <div class="px-5 pb-3 flex items-center gap-2">
                <label for="day-input" class="text-sm font-medium text-gray-700">Day:</label>
                <input type="number" 
                       id="day-input" 
                       value="{{ request('day', 1) }}" 
                       min="1" 
                       max="{{ $tour->days }}"
                       class="w-16 px-2 py-1 rounded border border-gray-300 text-center text-sm">
            </div>

            <!-- Places List (Scrollable) -->
            <div class="flex-1 overflow-y-auto px-5 pb-24">
                <div id="results-list">
                    <p class="text-gray-400 text-center py-8">Search results will appear here...</p>
                </div>
            </div>
        </div>

        <!-- Footer Actions (Fixed) -->
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 px-5 py-4 flex gap-3 z-20">
            <a href="{{ route('tour.show', $tour->id) }}" 
               class="flex-1 py-3 text-center border border-gray-300 rounded-lg text-gray-700 font-medium no-underline">
                cancel
            </a>
            <button id="add-button" 
                    disabled
                    class="flex-1 py-3 bg-[#007aff] text-white rounded-lg font-medium disabled:bg-gray-300 disabled:cursor-not-allowed">
                Add(0)
            </button>
        </div>
    </div>

    <!-- Hidden Form for Submission -->
    <form id="waypoint-form" method="POST" action="{{ route('waypoint.store', ['id' => $tour->id]) }}" style="display: none;">
        @csrf
        <input type="hidden" name="days" value="1">
        <input type="hidden" name="waypoints_json" id="waypoints-json">
        <input type="hidden" name="place_search" id="hidden-place-search">
    </form>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.googleMaps.api_key') }}&libraries=places&callback=initMap" async defer></script>

    <script>
        let map;
        let service;
        let selectedPlaces = [];
        let currentResults = [];
        let placeCache = new Map();

        function initMap() {
            const tourPlace = "{{ $tour->place }}";
            const defaultLocation = { lat: 35.1815, lng: 136.9066 }; // Nagoya

            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 13,
                center: defaultLocation,
                disableDefaultUI: true,
                zoomControl: false,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: false,
            });

            service = new google.maps.places.PlacesService(map);

            // Removed automatic search for faster page load
            // Users can manually search using the search bar

            const input = document.getElementById('place-search-input');
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    performSearch(input.value);
                }
            });

            document.getElementById('add-button').addEventListener('click', submitForm);
        }

        let markers = [];

        function performSearch(query) {
            const request = {
                query: query,
            };

            service.textSearch(request, (results, status) => {
                if (status === google.maps.places.PlacesServiceStatus.OK && results) {
                    currentResults = results;
                    
                    // Clear existing markers
                    markers.forEach(marker => marker.setMap(null));
                    markers = [];

                    const bounds = new google.maps.LatLngBounds();

                    results.forEach(place => {
                        placeCache.set(place.place_id, place);

                        // Create marker
                        const marker = new google.maps.Marker({
                            map: map,
                            position: place.geometry.location,
                            title: place.name,
                        });
                        
                        // Add click listener to select/deselect
                        marker.addListener('click', () => {
                            toggleSelection(place.place_id);
                        });

                        markers.push(marker);
                        bounds.extend(place.geometry.location);
                    });

                    renderList(results);
                    
                    if (results.length > 0) {
                        map.fitBounds(bounds);
                    }
                }
            });
        }

        function renderList(places) {
            const listContainer = document.getElementById('results-list');
            
            // Build HTML string first to minimize DOM reflows
            const htmlParts = places.map((place, index) => {
                const photoUrl = place.photos && place.photos.length > 0 
                    ? place.photos[0].getUrl({ maxWidth: 100, maxHeight: 100 }) 
                    : ''; 
                
                const rating = place.rating ? place.rating.toFixed(1) : '';
                const isSelected = selectedPlaces.includes(place.place_id);

                return `
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div class="flex items-center gap-3 flex-grow">
                            <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                ${photoUrl ? `<img src="${photoUrl}" class="w-full h-full object-cover" loading="lazy">` : ''}
                            </div>
                            <div class="flex-grow">
                                <div class="font-medium text-sm">${place.name}</div>
                                ${rating ? `
                                    <div class="flex items-center text-xs text-gray-600 mt-1">
                                        <span class="material-icons text-yellow-500 text-sm mr-1">star</span>
                                        <span>${rating}</span>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                        <div class="checkbox-container">
                            <input type="checkbox" 
                                   id="place-${index}" 
                                   class="hidden place-checkbox"
                                   onchange="toggleSelection('${place.place_id}')" 
                                   ${isSelected ? 'checked' : ''}>
                            <label for="place-${index}" class="material-icons cursor-pointer text-2xl ${isSelected ? 'text-[#007aff]' : 'text-gray-400'}">
                                ${isSelected ? 'check_box' : 'check_box_outline_blank'}
                            </label>
                        </div>
                    </div>
                `;
            });

            listContainer.innerHTML = htmlParts.join('');
        }

        window.toggleSelection = function(placeId) {
            if (selectedPlaces.includes(placeId)) {
                selectedPlaces = selectedPlaces.filter(id => id !== placeId);
            } else {
                selectedPlaces.push(placeId);
            }
            updateAddButton();
            renderList(currentResults);
        }

        function updateAddButton() {
            const btn = document.getElementById('add-button');
            btn.innerText = `Add(${selectedPlaces.length})`;
            btn.disabled = selectedPlaces.length === 0;
        }

        function submitForm() {
            if (selectedPlaces.length === 0) return;

            const dayNumber = document.getElementById('day-input').value;

            const selectedData = selectedPlaces.map(placeId => {
                const p = placeCache.get(placeId);
                if (!p) return null;

                return {
                    name: p.name,
                    google_place_id: p.place_id,
                    lat: p.geometry.location.lat(),
                    lng: p.geometry.location.lng(),
                    image_url: p.photos && p.photos.length > 0 ? p.photos[0].getUrl({ maxWidth: 400 }) : null,
                    rating: p.rating || null,
                    day_number: dayNumber 
                };
            }).filter(item => item !== null);

            document.getElementById('waypoints-json').value = JSON.stringify(selectedData);
            
            const hiddenDaysInput = document.querySelector('#waypoint-form input[name="days"]');
            if (hiddenDaysInput) {
                hiddenDaysInput.value = dayNumber;
            }
            
            const searchInput = document.getElementById('place-search-input');
            const hiddenSearchInput = document.getElementById('hidden-place-search');
            if (hiddenSearchInput) {
                hiddenSearchInput.value = searchInput.value;
            }

            document.getElementById('waypoint-form').submit();
        }
    </script>
</x-layout>