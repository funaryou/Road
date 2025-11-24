<x-layout title="Register">
    <x-auth-layout>
    <div class="absolute bottom-0 w-full h-auto py-6 px-8 bg-white rounded-t-[40px]">
        <form action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-col items-center justify-center gap-6">
                <p class="text-2xl font-bold">Sign up</p>
                <x-input type="text" name="name" placeholder="username" />
                <x-input type="email" name="email" placeholder="email" />
                <x-input type="text" name="phone_number" placeholder="tel" />
                <x-input type="file" name="icon" placeholder="icon" class="bg-gray-200 border-none" />
                <x-input type="password" name="password" placeholder="passward" />
                <x-input type="password" name="password_confirmation" placeholder="passward confirmation" />
                <button class="w-60 h-auto p-2 bg-[#024887] rounded-lg text-xl text-white font-bold" type="submit">sign up</button>
            </div>
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
    </x-auth-layout>
</x-layout>