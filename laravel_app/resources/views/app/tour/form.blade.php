<x-layout title="CreateTour">
    <div>
        <form method="POST" action="{{ route('tour.store') }}">
            @csrf
                <input type="text" name="title" value="Travel schedule">
                <input type="text" name="place" placeholder="Place of departure">
                <input type="text" name="destination" placeholder="Destination">
                <input type="number" name="days" placeholder="Days (e.g. 3)" min="1" required>
                <button type="submit">NEXT</button>
        </form>
            <!-- エラーメッセージ -->
        @if ($errors->any())
            <div class="text-red-500">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    <p><a href="{{ route('tour.select') }}">一覧に戻る</a></p>
</x-layout>