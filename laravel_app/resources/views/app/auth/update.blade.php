<x-layout>
    <h1>Update</h1>
    <div>
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="text" name="name" value="{{ Auth::user()->name }}">
            <input type="email" name="email" value="{{ Auth::user()->email }}">
            <input type="text" name="phone_number" value="{{ Auth::user()->phone_number }}">
            <input type="file" name="icon">
            <label for="icon"><img src="{{ asset('storage/' . Auth::user()->icon) }}" alt="icon"></label>
            <input type="date" name="birthday" value="{{ Auth::user()->birthday }}">
            <select name="gender">
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
            <button type="submit">Update</button>
        </form>
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
</x-layout>