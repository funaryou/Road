<x-layout title="Edit Profile">
    <div class="min-h-screen bg-white pb-6">
        <!-- Header -->
        <header class="flex items-center px-5 py-4">
            <a href="{{ url()->previous() }}" class="mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </a>
            <h1 class="text-lg font-medium">edit profile</h1>
        </header>

        <!-- Photo Edit Section -->
        <section class="py-8 px-5">
            <div class="flex justify-center items-center gap-4 mb-6">
                <!-- Left Shachihoko -->
                <div class="w-16 h-16">
                    <img src="{{ asset('assets/images/shachihoko-2.svg') }}" alt="" class="w-full h-full object-contain">
                </div>

                <!-- Profile Photo -->
                <div class="relative">
                    <img id="profilePhotoImg" 
                         src="{{ asset('storage/' . $user->icon) }}" 
                         alt="Profile photo" 
                         class="w-32 h-32 rounded-full object-cover border-4 border-gray-200">
                </div>

                <!-- Right Shachihoko -->
                <div class="w-16 h-16">
                    <img src="{{ asset('assets/images/shachihoko-1.svg') }}" alt="" class="w-full h-full object-contain">
                </div>
            </div>

            <div class="text-center">
                <button type="button" 
                        id="editPhotoBtn" 
                        class="px-6 py-2 bg-[#007aff] text-white rounded-full text-sm font-medium">
                    Edit Photo
                </button>
                <input type="file" id="photoInput" accept="image/*" class="hidden">
            </div>
        </section>

        <!-- Profile Form -->
        <form id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="px-5 space-y-4">
            @csrf
            @method('PUT')

            <!-- Hidden file input for form submission -->
            <input type="file" name="icon" id="iconInput" class="hidden">

            <!-- Username -->
            <div class="space-y-2">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" 
                       id="username" 
                       name="name" 
                       value="{{ old('name', $user->name) }}" 
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#007aff]">
            </div>

            <!-- Email -->
            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email', $user->email) }}" 
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#007aff]">
            </div>

            <!-- Phone -->
            <div class="space-y-2">
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="tel" 
                       id="phone" 
                       name="phone_number" 
                       value="{{ old('phone_number', $user->phone_number) }}" 
                       placeholder="e.g. 090-1234-5678"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#007aff]">
            </div>

            <!-- Birthday -->
            <div class="space-y-2">
                <label for="birthday" class="block text-sm font-medium text-gray-700">Birthday</label>
                <input type="date" 
                       id="birthday" 
                       name="birthday" 
                       value="{{ old('birthday', $user->birthday) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#007aff]">
            </div>

            <!-- Gender -->
            <div class="space-y-2">
                <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                <select id="gender" 
                        name="gender"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#007aff]">
                    <option value="">Please select</option>
                    <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                    <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Other</option>
                    <option value="none" {{ old('gender', $user->gender) === 'none' ? 'selected' : '' }}>Prefer not to say</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-4">
                <a href="{{ url()->previous() }}" 
                   class="flex-1 py-3 text-center border border-gray-300 rounded-lg text-gray-700 font-medium no-underline">
                    Cancel
                </a>
                <button type="submit" 
                        class="flex-1 py-3 bg-[#007aff] text-white rounded-lg font-medium">
                    Save
                </button>
            </div>
        </form>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="px-5 mt-4">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Image preview
        const editBtn = document.getElementById('editPhotoBtn');
        const photoInput = document.getElementById('photoInput');
        const iconInput = document.getElementById('iconInput');
        const profileImg = document.getElementById('profilePhotoImg');

        editBtn.addEventListener('click', () => photoInput.click());

        photoInput.addEventListener('change', (e) => {
            const file = e.target.files && e.target.files[0];
            if (!file) return;
            
            // Preview image
            const reader = new FileReader();
            reader.onload = (ev) => {
                profileImg.src = ev.target.result;
            };
            reader.readAsDataURL(file);

            // Copy file to form input
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            iconInput.files = dataTransfer.files;
        });
    </script>
</x-layout>