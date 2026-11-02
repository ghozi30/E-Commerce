@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Add New Category</h1>
        <p class="text-sm text-gray-500">Create a new product category or subcategory</p>
    </div>
    <a href="{{ route('admin.categories') }}" class="border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2">
        <i class="fa-solid fa-arrow-left"></i> Back to Categories
    </a>
</div>

<div class="max-w-3xl mx-auto">
    <form action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Category Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Electronics" class="w-full border px-4 py-2.5 rounded-lg outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('name') border-red-500 @enderror" required>
                @error('name')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>
            
            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Category Slug *</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug') }}" placeholder="electronics" class="w-full border px-4 py-2.5 rounded-lg bg-gray-50 outline-none focus:ring-1 focus:ring-primary focus:border-primary @error('slug') border-red-500 @enderror" required>
                @error('slug')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div>
            <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-1">Parent Category</label>
            <select id="parent_id" name="parent_id" class="w-full border px-4 py-2.5 rounded-lg outline-none bg-white focus:ring-1 focus:ring-primary focus:border-primary">
                <option value="">None (Top Level Category)</option>
                @foreach($parents as $parent)
                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                        {{ $parent->name }}
                    </option>
                @endforeach
            </select>
            @error('parent_id')
                <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Category Image</label>
            <div class="relative flex items-center justify-center w-full h-44">
                <label for="category-image" class="relative flex flex-col items-center justify-center w-full h-full border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition overflow-hidden">
                    <div id="upload-content" class="flex flex-col items-center justify-center pt-5 pb-6 z-10 text-center">
                        <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                        <p class="text-sm text-gray-600 font-medium">Click to upload category image</p>
                        <p class="text-xs text-gray-400 mt-1">PNG, JPG, JPEG, WEBP (Max: 2MB)</p>
                    </div>

                    <img id="image-preview" class="hidden absolute inset-0 w-full h-full object-contain p-4 z-20 bg-white" src="" alt="Category Image Preview">

                    <input id="category-image" name="image" type="file" class="hidden" accept="image/png, image/jpeg, image/jpg, image/webp" />
                </label>

                <button type="button" id="remove-image-btn" class="hidden absolute top-3 right-3 z-30 bg-white text-red-500 hover:text-white hover:bg-red-500 rounded-full w-8 h-8 flex items-center justify-center shadow-md border border-gray-200 transition focus:outline-none" title="Remove image">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            @error('image')
                <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
            <a href="{{ route('admin.categories') }}" class="px-6 py-2.5 border rounded-lg hover:bg-gray-50 transition text-sm text-gray-700 font-medium">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-blue-600 transition text-sm font-medium shadow-sm">Save Category</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        const imageInput = document.getElementById('category-image');
        const uploadContent = document.getElementById('upload-content');
        const imagePreview = document.getElementById('image-preview');
        const removeBtn = document.getElementById('remove-image-btn');

        // Auto-generate slug from name
        nameInput.addEventListener('input', function() {
            slugInput.value = nameInput.value
                .toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        });

        // Handle file selection preview
        imageInput.addEventListener('change', function(e) {
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

        // Remove selected image
        removeBtn.addEventListener('click', function() {
            resetImage();
        });

        function resetImage() {
            imageInput.value = '';
            imagePreview.src = '';
            imagePreview.classList.add('hidden');
            removeBtn.classList.add('hidden');
            uploadContent.classList.remove('hidden');
        }
    });
</script>
@endpush
