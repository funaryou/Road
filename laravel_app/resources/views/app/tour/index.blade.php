<x-layout title="TourList">
    <div>
        <div class="">
            <a href="{{ route('tour.form') }}">Create Plan</a>
        </div>
        @foreach($tours as $tour)
            <div>
                <img src="{{ $tour->topImage }}" alt="">
            </div>
            <div>
                <p>{{ $tour->name }}</p>
                <a href="{{ route('tour.show', ['id' => $tour->id]) }}">{{ $tour->days }} days / {{ $tour->waypointsCount }} places</a>
            </div>
        @endforeach
    </div>
</x-layout>