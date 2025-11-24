<x-layout title="Top">
    <div class="relative w-full h-screen overflow-hidden">
        <!-- Map -->
        <div id="map" class="w-full h-full"></div>

        <!-- Search Bar -->
        <div class="absolute top-12 left-5 right-5 z-10">
            <div class="bg-white rounded-lg shadow-md flex items-center px-4 py-3">
                <span class="material-icons text-[#007aff] mr-2">search</span>
                <input type="text" 
                       id="search-input" 
                       placeholder="Nagoya Food" 
                       class="flex-grow outline-none text-base text-gray-700 placeholder-gray-400">
            </div>
        </div>

        <!-- Cards Carousel -->
        <div class="absolute bottom-24 left-0 right-0 z-10">
            <div id="cards-container" class="flex overflow-x-auto gap-4 px-5 pb-5 snap-x snap-mandatory hide-scrollbar">
                <!-- Cards will be injected here -->
            </div>
        </div>
    </div>

    <x-navigation active-page="home" />

    <style>
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.googleMaps.api_key') }}&libraries=places&callback=initMap" async defer></script>

    <script>
        let map;
        let service;
        let markers = [];
        let currentPlaces = [];

        function initMap() {
            const defaultLocation = { lat: 35.1815, lng: 136.9066 }; // Nagoya

            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 13,
                center: defaultLocation,
                disableDefaultUI: true,
                zoomControl: false,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: false,
                styles: [
                    {
                        featureType: "poi",
                        elementType: "labels",
                        stylers: [{ visibility: "off" }],
                    },
                ],
            });

            service = new google.maps.places.PlacesService(map);

            // Check for place_id in URL
            const urlParams = new URLSearchParams(window.location.search);
            const placeId = urlParams.get('place_id');

            if (placeId) {
                getPlaceDetails(placeId);
            }

            const input = document.getElementById('search-input');
            
            // Search on Enter key
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    performSearch(input.value);
                    input.blur();
                }
            });
        }

        function getPlaceDetails(placeId) {
            const request = {
                placeId: placeId,
                fields: ['name', 'geometry', 'photos', 'rating', 'place_id', 'types']
            };

            service.getDetails(request, (place, status) => {
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                    // Clear existing results
                    clearResults();

                    const bounds = new google.maps.LatLngBounds();
                    currentPlaces = [place]; // Treat as a single result

                    createMarker(place, 0);
                    bounds.extend(place.geometry.location);

                    renderCards([place]);

                    map.setCenter(place.geometry.location);
                    map.setZoom(15); // Zoom in for specific place
                    
                    // Since it's a liked place (coming from profile), set the heart to red
                    setTimeout(() => {
                        const icon = document.getElementById('like-icon-0');
                        if (icon) {
                            icon.classList.remove('text-gray-400');
                            icon.classList.add('text-red-500');
                        }
                    }, 100);
                }
            });
        }

        function performSearch(query) {
            const request = {
                query: query,
                fields: ['name', 'geometry', 'photos', 'rating', 'place_id', 'types'],
            };

            service.textSearch(request, (results, status) => {
                if (status === google.maps.places.PlacesServiceStatus.OK && results) {
                    // Clear existing markers and cards
                    clearResults();

                    const bounds = new google.maps.LatLngBounds();
                    currentPlaces = results;

                    results.forEach((place, index) => {
                        createMarker(place, index);
                        bounds.extend(place.geometry.location);
                    });

                    renderCards(results);

                    if (results.length > 0) {
                        map.fitBounds(bounds);
                    }
                }
            });
        }

        function clearResults() {
            markers.forEach(marker => marker.setMap(null));
            markers = [];
            document.getElementById('cards-container').innerHTML = '';
        }

        function createMarker(place, index) {
            const marker = new google.maps.Marker({
                map: map,
                position: place.geometry.location,
                title: place.name,
                // Simple red marker
            });

            marker.addListener('click', () => {
                // Scroll to corresponding card
                const card = document.getElementById(`card-${index}`);
                if (card) {
                    card.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                }
            });

            markers.push(marker);
        }

        function renderCards(places) {
            const container = document.getElementById('cards-container');
            
            const html = places.map((place, index) => {
                const photoUrl = place.photos && place.photos.length > 0 
                    ? place.photos[0].getUrl({ maxWidth: 400 }) 
                    : 'https://via.placeholder.com/400x200?text=No+Image';
                
                const rating = place.rating ? place.rating.toFixed(1) : 'N/A';
                const types = place.types ? place.types.slice(0, 2).join(', ').replace(/_/g, ' ') : '';

                return `
                    <div id="card-${index}" class="snap-center flex-shrink-0 w-[280px] bg-white rounded-2xl shadow-lg overflow-hidden relative">
                        <div class="h-32 w-full relative">
                            <img src="${photoUrl}" class="w-full h-full object-cover" loading="lazy">
                            <button onclick="toggleLike('${index}')" 
                                    class="absolute top-2 right-2 w-8 h-8 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center shadow-sm z-10">
                                <span id="like-icon-${index}" class="material-icons text-gray-400 text-xl">favorite</span>
                            </button>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-gray-900 text-sm mb-1 truncate">${place.name}</h3>
                            <p class="text-xs text-gray-500 mb-2 truncate">${types}</p>
                            <div class="flex items-center">
                                <span class="material-icons text-yellow-400 text-sm mr-1">star</span>
                                <span class="text-xs font-bold text-gray-900">${rating}</span>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            container.innerHTML = html;
        }

        window.toggleLike = async function(index) {
            const place = currentPlaces[index];
            const icon = document.getElementById(`like-icon-${index}`);
            
            // Optimistic UI update
            const isCurrentlyLiked = icon.classList.contains('text-red-500');
            if (isCurrentlyLiked) {
                icon.classList.remove('text-red-500');
                icon.classList.add('text-gray-400');
            } else {
                icon.classList.remove('text-gray-400');
                icon.classList.add('text-red-500');
            }

            try {
                const response = await fetch("{{ route('place.like') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        google_place_id: place.place_id,
                        name: place.name,
                        lat: place.geometry.location.lat(),
                        lng: place.geometry.location.lng(),
                        image_url: place.photos && place.photos.length > 0 ? place.photos[0].getUrl({ maxWidth: 400 }) : null,
                        rating: place.rating || null
                    })
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const data = await response.json();
                
                // Correct UI based on server response
                if (data.liked) {
                    icon.classList.remove('text-gray-400');
                    icon.classList.add('text-red-500');
                } else {
                    icon.classList.remove('text-red-500');
                    icon.classList.add('text-gray-400');
                }

            } catch (error) {
                console.error('Error:', error);
                // Revert UI on error
                if (isCurrentlyLiked) {
                    icon.classList.remove('text-gray-400');
                    icon.classList.add('text-red-500');
                } else {
                    icon.classList.remove('text-red-500');
                    icon.classList.add('text-gray-400');
                }
            }
        }
    </script>
</x-layout>
