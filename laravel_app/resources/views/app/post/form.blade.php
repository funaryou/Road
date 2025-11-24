<x-layout title="Create Post">
    <x-header type="back" title="Posts" />
    
    <div class="pb-[120px] px-5 pt-6">
        <form method="POST" action="{{ route('post.store') }}" enctype="multipart/form-data" id="post-form">
            @csrf
            
            <!-- Image Preview Area -->
            <div class="mb-6">
                <label for="files" class="block mb-2 text-sm font-medium text-gray-700">Select Images</label>
                <div id="image-preview-container" class="grid grid-cols-2 gap-3 mb-3">
                    <!-- Preview images will be inserted here -->
                </div>
                <input type="file" name="files[]" id="files" multiple accept="image/*" class="hidden">
                <button type="button" onclick="document.getElementById('files').click()" 
                        class="w-full py-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-500 hover:border-[#024887] hover:text-[#024887] transition">
                    + Add Images
                </button>
            </div>

            <!-- Description Textarea -->
            <div class="mb-6">
                <label for="content" class="block mb-2 text-sm font-medium text-gray-700">Description</label>
                <textarea id="content" 
                          name="content" 
                          rows="6" 
                          placeholder="Add description..."
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#024887] focus:border-transparent resize-none"></textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full py-3 bg-[#024887] text-white font-bold rounded-lg hover:bg-[#023a6e] transition">
                Post
            </button>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <ul class="text-red-600 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </form>
    </div>

    <x-navigation active-page="home" />

    <script>
        const fileInput = document.getElementById('files');
        const previewContainer = document.getElementById('image-preview-container');
        let selectedFiles = [];

        fileInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            
            files.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    selectedFiles.push(file);
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewDiv = document.createElement('div');
                        previewDiv.className = 'relative aspect-square rounded-lg overflow-hidden bg-gray-100';
                        previewDiv.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-full object-cover" alt="preview">
                            <button type="button" 
                                    onclick="removeImage(${selectedFiles.length - 1})"
                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition">
                                ×
                            </button>
                        `;
                        previewContainer.appendChild(previewDiv);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        function removeImage(index) {
            selectedFiles.splice(index, 1);
            updatePreview();
        }

        function updatePreview() {
            previewContainer.innerHTML = '';
            const dataTransfer = new DataTransfer();
            
            selectedFiles.forEach((file, index) => {
                dataTransfer.items.add(file);
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'relative aspect-square rounded-lg overflow-hidden bg-gray-100';
                    previewDiv.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover" alt="preview">
                        <button type="button" 
                                onclick="removeImage(${index})"
                                class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition">
                            ×
                        </button>
                    `;
                    previewContainer.appendChild(previewDiv);
                };
                reader.readAsDataURL(file);
            });
            
            fileInput.files = dataTransfer.files;
        }
    </script>
</x-layout>