@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Edit Brand</h1>
        <p class="text-sm text-gray-500">Update brand details and logo</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.brands') }}" class="border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Cancel
        </a>
    </div>
</div>

<div class="max-w-3xl mx-auto">
    <form action="{{ route('admin.brand.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 space-y-6">
        @csrf
        @method('PUT')
        
        <input type="hidden" name="id" value="{{ $brand->id }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Brand Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $brand->name) }}" class="w-full border px-4 py-2.5 rounded-lg outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('name') border-red-500 @enderror" required>
                @error('name')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>
            
            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Brand Slug *</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $brand->slug) }}" class="w-full border px-4 py-2.5 rounded-lg bg-gray-50 outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('slug') border-red-500 @enderror" required>
                @error('slug')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-6 items-start pt-2">
            <!-- Current Logo -->
            <div class="w-full md:w-1/3">
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Logo</label>
                <div class="h-44 w-full border rounded-lg bg-gray-50 flex items-center justify-center p-4">
                    @if($brand->image)
                        <img src="{{ asset('uploads/brands/' . $brand->image) }}" class="max-h-full max-w-full object-contain" alt="{{ $brand->name }}">
                    @else
                        <span class="text-gray-400 text-sm">No logo uploaded</span>
                    @endif
                </div>
            </div>

            <!-- Upload New Logo -->
            <div class="w-full md:w-2/3">
                <label class="block text-sm font-medium text-gray-700 mb-2">Change Brand Logo</label>
                <div class="relative flex items-center justify-center w-full h-44">
                    <label for="brand-image" class="relative flex flex-col items-center justify-center w-full h-full border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition overflow-hidden">
                        <div id="upload-content" class="text-center z-10 p-4">
                            <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-600 font-medium">Click to upload new logo</p>
                            <p class="text-xs text-gray-400 mt-1">Leave empty to keep current logo</p>
                        </div>

                        <img id="image-preview" class="hidden absolute inset-0 w-full h-full object-contain p-4 z-20 bg-white" src="" alt="New Logo Preview">

                        <input id="brand-image" name="image" type="file" class="hidden" accept="image/png, image/jpeg, image/jpg, image/webp" />
                    </label>

                    <button type="button" id="remove-logo-btn" class="hidden absolute top-3 right-3 z-30 bg-white text-red-500 hover:text-white hover:bg-red-500 rounded-full w-8 h-8 flex items-center justify-center shadow-md border border-gray-200 transition focus:outline-none" title="Remove image">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                @error('image')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.brands') }}" class="px-6 py-2.5 border rounded-lg hover:bg-gray-50 transition text-sm text-gray-700 font-medium">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-blue-600 transition text-sm font-medium shadow-sm">Save Changes</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        const logoInput = document.getElementById('brand-image');
        const uploadContent = document.getElementById('upload-content');
        const imagePreview = document.getElementById('image-preview');
        const removeBtn = document.getElementById('remove-logo-btn');

        // Auto-generate slug from name on edit if user changes name
        nameInput.addEventListener('input', function() {
            slugInput.value = nameInput.value
                .toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        });

        // Handle new file preview
        logoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                imagePreview.src = URL.createObjectURL(file);
                uploadContent.classList.add('hidden');
                imagePreview.classList.remove('hidden');
                removeBtn.classList.remove('hidden');
            } else {
                resetImage();
            }
        });

        // Remove new image
        removeBtn.addEventListener('click', function() {
            resetImage();
        });

        function resetImage() {
            logoInput.value = '';
            imagePreview.src = '';
            imagePreview.classList.add('hidden');
            removeBtn.classList.add('hidden');
            uploadContent.classList.remove('hidden');
        }
    });
</script>
@endpush

