<x-layout title="Add Waypoint">
    <div>
        <!-- Map Area -->
        <div id="map" style="height: 400px;"></div>

        <!-- Search Area -->
        <div>
            <div>
                <label for="day-input">Day:</label>
                <input type="number" id="day-input" value="1" min="1" max="{{ $tour->days }}">
            </div>

            <input type="text" id="place-search-input" 
                   placeholder="Search for a place (e.g. Hotel, Station)" 
                   value="{{ old('place_search', $tour->place) }}">
        </div>

        <!-- Results List -->
        <div id="results-list">
            <!-- JS will populate this -->
            <p>Search results will appear here...</p>
        </div>

        <!-- Action Buttons -->
        <div>
            <a href="{{ route('tour.show', $tour->id) }}">Cancel</a>
            <button id="add-button" disabled>
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
        let selectedPlaces = []; // Array of place_ids to keep order
        let currentResults = [];
        let placeCache = new Map(); // Cache place data by ID

        function initMap() {
            // ... (省略なし) ...
            const tourPlace = "{{ $tour->place }}";
            const defaultLocation = { lat: 35.6812, lng: 139.7671 }; // Tokyo

            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 13,
                center: defaultLocation,
            });

            service = new google.maps.places.PlacesService(map);

            // Initial Search
            if (tourPlace) {
                performSearch(tourPlace);
            }

            // Search Input Listener
            const input = document.getElementById('place-search-input');
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    performSearch(input.value);
                }
            });

            // Add Button Listener
            document.getElementById('add-button').addEventListener('click', submitForm);
        }

        function performSearch(query) {
            const request = {
                query: query,
            };

            service.textSearch(request, (results, status) => {
                if (status === google.maps.places.PlacesServiceStatus.OK && results) {
                    currentResults = results;
                    
                    // Cache results
                    results.forEach(place => {
                        placeCache.set(place.place_id, place);
                    });

                    renderList(results);
                    
                    // Center map on first result
                    if (results.length > 0) {
                        map.setCenter(results[0].geometry.location);
                    }
                }
            });
        }

        // ... (renderList, toggleSelection, updateAddButton は変更なし) ...
        function renderList(places) {
            const listContainer = document.getElementById('results-list');
            listContainer.innerHTML = '';

            places.forEach((place, index) => {
                const photoUrl = place.photos && place.photos.length > 0 
                    ? place.photos[0].getUrl({ maxWidth: 100, maxHeight: 100 }) 
                    : ''; 
                
                const rating = place.rating ? `★${place.rating.toFixed(1)}` : '';
                const isSelected = selectedPlaces.includes(place.place_id);

                const itemDiv = document.createElement('div');
                // Removed inline styles

                let imgHtml = '';
                if (photoUrl) {
                    imgHtml = `<img src="${photoUrl}" width="50" height="50">`;
                }

                itemDiv.innerHTML = `
                    ${imgHtml}
                    <span>${place.name}</span>
                    <span>${rating}</span>
                    <input type="checkbox" onchange="toggleSelection('${place.place_id}')" ${isSelected ? 'checked' : ''}>
                `;
                
                listContainer.appendChild(itemDiv);
            });
        }

        window.toggleSelection = function(placeId) {
            if (selectedPlaces.includes(placeId)) {
                selectedPlaces = selectedPlaces.filter(id => id !== placeId);
            } else {
                selectedPlaces.push(placeId);
            }
            updateAddButton();
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
                if (!p) return null; // Should not happen if cached correctly

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
            
            // Update hidden days input
            const hiddenDaysInput = document.querySelector('#waypoint-form input[name="days"]');
            if (hiddenDaysInput) {
                hiddenDaysInput.value = dayNumber;
            }
            
            // 検索ワードをhidden inputにセット (復活)
            const searchInput = document.getElementById('place-search-input');
            const hiddenSearchInput = document.getElementById('hidden-place-search');
            if (hiddenSearchInput) {
                hiddenSearchInput.value = searchInput.value;
            }

            document.getElementById('waypoint-form').submit();
        }
    </script>
</x-layout>