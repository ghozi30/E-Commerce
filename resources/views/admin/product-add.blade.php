@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Add New Product</h1>
        <p class="text-sm text-gray-500">Create a new item in your catalog</p>
    </div>
    <a href="{{ route('admin.products') }}" class="border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2">
        <i class="fa-solid fa-arrow-left"></i> Back to Products
    </a>
</div>

<form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Basic info & Pricing -->
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Basic Information</h3>
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Modern Linen Sofa" class="w-full border px-4 py-2.5 rounded-lg outline-none focus:ring-1 focus:ring-primary focus:border-primary text-sm @error('name') border-red-500 @enderror" required>
                        @error('name')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug *</label>
                        <input type="text" id="slug" name="slug" value="{{ old('slug') }}" placeholder="modern-linen-sofa" class="w-full border px-4 py-2.5 rounded-lg bg-gray-50 outline-none focus:ring-1 focus:ring-primary focus:border-primary text-sm @error('slug') border-red-500 @enderror" required>
                        @error('slug')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="short_description" class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                        <textarea id="short_description" name="short_description" rows="3" placeholder="Brief summary of product features..." class="w-full border px-4 py-2.5 rounded-lg outline-none focus:ring-1 focus:ring-primary focus:border-primary text-sm @error('short_description') border-red-500 @enderror">{{ old('short_description') }}</textarea>
                        @error('short_description')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="6" placeholder="Detailed product specifications..." class="w-full border px-4 py-2.5 rounded-lg outline-none focus:ring-1 focus:ring-primary focus:border-primary text-sm @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Pricing & Inventory</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="regular_price" class="block text-sm font-medium text-gray-700 mb-1">Regular Price ($) *</label>
                        <input type="number" step="0.01" id="regular_price" name="regular_price" value="{{ old('regular_price') }}" placeholder="0.00" class="w-full border px-4 py-2.5 rounded-lg outline-none focus:ring-1 focus:ring-primary focus:border-primary text-sm @error('regular_price') border-red-500 @enderror" required>
                        @error('regular_price')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="sale_price" class="block text-sm font-medium text-gray-700 mb-1">Sale Price ($)</label>
                        <input type="number" step="0.01" id="sale_price" name="sale_price" value="{{ old('sale_price') }}" placeholder="0.00" class="w-full border px-4 py-2.5 rounded-lg outline-none focus:ring-1 focus:ring-primary focus:border-primary text-sm @error('sale_price') border-red-500 @enderror">
                        @error('sale_price')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="SKU" class="block text-sm font-medium text-gray-700 mb-1">SKU *</label>
                        <input type="text" id="SKU" name="SKU" value="{{ old('SKU') }}" placeholder="e.g. PROD-101" class="w-full border px-4 py-2.5 rounded-lg outline-none focus:ring-1 focus:ring-primary focus:border-primary text-sm @error('SKU') border-red-500 @enderror" required>
                        @error('SKU')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                        <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 10) }}" placeholder="10" class="w-full border px-4 py-2.5 rounded-lg outline-none focus:ring-1 focus:ring-primary focus:border-primary text-sm @error('quantity') border-red-500 @enderror" required>
                        @error('quantity')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="stock_status" class="block text-sm font-medium text-gray-700 mb-1">Stock Status *</label>
                        <select id="stock_status" name="stock_status" class="w-full border px-4 py-2.5 rounded-lg outline-none focus:ring-1 focus:ring-primary focus:border-primary text-sm bg-white">
                            <option value="instock" {{ old('stock_status') == 'instock' ? 'selected' : '' }}>In Stock</option>
                            <option value="outofstock" {{ old('stock_status') == 'outofstock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                        @error('stock_status')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

        </div>

        <!-- Right: Organization & Images -->
        <div class="space-y-6">
            
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Publish</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="featured" name="featured" value="1" {{ old('featured') ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary">
                        <label for="featured" class="text-sm font-medium text-gray-700 cursor-pointer">Featured Product</label>
                    </div>
                    
                    <button type="submit" class="w-full bg-primary hover:bg-blue-600 text-white py-2.5 rounded-lg text-sm font-medium transition shadow-sm flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check"></i> Save Product
                    </button>
                    <a href="{{ route('admin.products') }}" class="block text-center w-full py-2 border rounded-lg hover:bg-gray-50 text-sm text-gray-600 font-medium transition">
                        Cancel
                    </a>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Organization</h3>
                <div class="space-y-4">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                        <select id="category_id" name="category_id" class="w-full border px-4 py-2.5 rounded-lg outline-none focus:ring-1 focus:ring-primary text-sm bg-white @error('category_id') border-red-500 @enderror" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="brand_id" class="block text-sm font-medium text-gray-700 mb-1">Brand *</label>
                        <select id="brand_id" name="brand_id" class="w-full border px-4 py-2.5 rounded-lg outline-none focus:ring-1 focus:ring-primary text-sm bg-white @error('brand_id') border-red-500 @enderror" required>
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Single Main Image -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Main Cover Image</h3>
                
                <div class="relative flex items-center justify-center w-full h-44">
                    <label for="product-image" class="relative flex flex-col items-center justify-center w-full h-full border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition overflow-hidden">
                        <div id="main-upload-content" class="text-center p-4">
                            <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-600 font-medium">Click to upload cover image</p>
                            <p class="text-xs text-gray-400 mt-1">PNG, JPG, WEBP (Max: 2MB)</p>
                        </div>

                        <img id="main-image-preview" class="hidden absolute inset-0 w-full h-full object-contain p-2 z-20 bg-white" src="" alt="Cover Preview">

                        <input id="product-image" name="image" type="file" class="hidden" accept="image/png, image/jpeg, image/jpg, image/webp" />
                    </label>

                    <button type="button" id="remove-main-btn" class="hidden absolute top-3 right-3 z-30 bg-white text-red-500 hover:text-white hover:bg-red-500 rounded-full w-8 h-8 flex items-center justify-center shadow-md border border-gray-200 transition focus:outline-none" title="Remove image">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                @error('image')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Gallery Images -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Gallery Images</h3>
                
                <label for="product-images" class="block border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition cursor-pointer">
                    <i class="fa-solid fa-images text-3xl text-gray-400 mb-2"></i>
                    <p class="text-sm text-gray-600 font-medium">Upload multiple gallery photos</p>
                    <p class="text-xs text-gray-400 mt-1">Select multiple files</p>
                    <input type="file" id="product-images" name="images[]" class="hidden" multiple accept="image/png, image/jpeg, image/jpg, image/webp">
                </label>

                <div id="gallery-preview-container" class="grid grid-cols-3 gap-2 mt-4"></div>
                @error('images.*')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

        </div>

    </div>
</form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        const mainInput = document.getElementById('product-image');
        const mainContent = document.getElementById('main-upload-content');
        const mainPreview = document.getElementById('main-image-preview');
        const removeMainBtn = document.getElementById('remove-main-btn');
        const galleryInput = document.getElementById('product-images');
        const galleryContainer = document.getElementById('gallery-preview-container');

        // Auto slug generator
        nameInput.addEventListener('input', function() {
            slugInput.value = nameInput.value
                .toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        });

        // Main cover image preview
        mainInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                mainPreview.src = URL.createObjectURL(file);
                mainContent.classList.add('hidden');
                mainPreview.classList.remove('hidden');
                removeMainBtn.classList.remove('hidden');
            } else {
                resetMainImage();
            }
        });

        removeMainBtn.addEventListener('click', function() {
            resetMainImage();
        });

        function resetMainImage() {
            mainInput.value = '';
            mainPreview.src = '';
            mainPreview.classList.add('hidden');
            removeMainBtn.classList.add('hidden');
            mainContent.classList.remove('hidden');
        }

        // Gallery multiple preview
        galleryInput.addEventListener('change', function(e) {
            galleryContainer.innerHTML = '';
            const files = e.target.files;
            if (files.length > 0) {
                Array.from(files).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const div = document.createElement('div');
                        div.className = 'relative h-20 bg-gray-50 rounded-lg border overflow-hidden p-1 flex items-center justify-center';
                        div.innerHTML = `<img src="${URL.createObjectURL(file)}" class="max-h-full max-w-full object-contain">`;
                        galleryContainer.appendChild(div);
                    }
                });
            }
        });
    });
</script>
@endpush

