<x-layout title="TourDetails">
    <div class="container">
        <p>Name: {{ $tour->name }}</p>
        <p>Days: {{ $tour->days }}</p>
        <p>Place: {{ $tour->place }}</p>
        <p>Destination: {{ $tour->destination }}</p>
        <a href="{{ route('tour.select') }}" class="btn btn-secondary">Back to Tours</a>
    </div>
    <!-- Map Container -->
    <div id="map" style="width: 100%; height: 400px; margin-top: 20px; background-color: #eee;"></div>

    <div>
        <h3>Waypoints</h3>
        <a href="{{ route('waypoint.form', ['id' => $tour->id]) }}" style="display: inline-block; margin-bottom: 10px; padding: 5px 10px; border: 1px solid #ccc; text-decoration: none;">Add Waypoint</a>
        
        @foreach($tour->waypoints as $waypoint)
            <div style="border: 1px solid #ddd; padding: 10px; margin-bottom: 10px;">
                @if($waypoint->image_url)
                    <img src="{{ $waypoint->image_url }}" alt="{{ $waypoint->name }}" style="width: 100px; height: 100px; object-fit: cover;">
                @endif
                <h4>{{ $waypoint->name }}</h4>
                @if($waypoint->rating)
                    <p>Rating: ★{{ $waypoint->rating }}</p>
                @endif
                <p>Day: {{ $waypoint->day_number }}</p>
            </div>
        @endforeach
    </div>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.googleMaps.api_key') }}&callback=initMap" async defer></script>
    <script>
        function initMap() {
            const waypoints = @json($tour->waypoints);
            const defaultLocation = { lat: 35.6812, lng: 139.7671 }; // Tokyo
            
            // Center map on the first waypoint or tour place (if available)
            let center = defaultLocation;
            if (waypoints.length > 0) {
                center = { lat: parseFloat(waypoints[0].lat), lng: parseFloat(waypoints[0].lng) };
            }

            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 10,
                center: center,
            });

            const bounds = new google.maps.LatLngBounds();

            waypoints.forEach((wp, index) => {
                const position = { lat: parseFloat(wp.lat), lng: parseFloat(wp.lng) };
                
                const marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    title: wp.name,
                    label: (index + 1).toString(), // Show sequence number (1, 2, 3...)
                });

                // InfoWindow for click
                const infoWindow = new google.maps.InfoWindow({
                    content: `<div><strong>${wp.name}</strong><br>Day ${wp.day_number}</div>`
                });

                marker.addListener("click", () => {
                    infoWindow.open(map, marker);
                });

                bounds.extend(position);
            });

            // Adjust map view to fit all markers if there are any
            if (waypoints.length > 0) {
                map.fitBounds(bounds);
            }
        }
    </script>
</x-layout>