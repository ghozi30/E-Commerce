@extends('layouts.admin')

@section('content')
<main class="flex-1 overflow-y-auto p-6 bg-gray-100">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Add New Slide</h1>
            <p class="text-sm text-gray-500">Create a promotional banner for the homepage</p>
        </div>
        <a href="{{ route('admin.slides') }}" class="border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Back to Slides
        </a>
    </div>

    @if ($errors->any())
        <div class="max-w-3xl mx-auto mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-3xl mx-auto">
        <form action="{{ route('admin.slide.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tagline *</label>
                    <input type="text" name="tagline" value="{{ old('tagline') }}" placeholder="e.g. Special Offer, Trending" required class="w-full border px-4 py-2 rounded-lg outline-none focus:ring-1 focus:ring-primary text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. New Stylish Decor Furniture" required class="w-full border px-4 py-2 rounded-lg outline-none focus:ring-1 focus:ring-primary text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle *</label>
                <input type="text" name="subtitle" value="{{ old('subtitle') }}" placeholder="e.g. Unique Furniture Style Design for Your Family" required class="w-full border px-4 py-2 rounded-lg outline-none focus:ring-1 focus:ring-primary text-sm">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Link URL *</label>
                    <input type="text" name="link" value="{{ old('link', '/shop') }}" placeholder="e.g. /shop or https://..." required class="w-full border px-4 py-2 rounded-lg outline-none focus:ring-1 focus:ring-primary text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                    <select name="status" required class="w-full border px-4 py-2 rounded-lg bg-white outline-none focus:ring-1 focus:ring-primary text-sm">
                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Slide Image *</label>
                <input type="file" name="image" id="slide-image-input" accept="image/*" required class="w-full border px-4 py-2 rounded-lg outline-none focus:ring-1 focus:ring-primary text-sm file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:bg-blue-600">
                <p class="text-xs text-gray-400 mt-1">Recommended transparent PNG or JPG (approx. 600x500px, max 2MB).</p>
                
                <div id="image-preview-container" class="mt-3 hidden">
                    <img id="image-preview" src="#" alt="Preview" class="h-32 object-contain rounded border border-gray-200 bg-gray-50 p-2">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('admin.slides') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-50 transition text-sm">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition text-sm font-medium shadow-sm">Save Slide</button>
            </div>
        </form>
    </div>
</main>
@endsection

@push('scripts')
<script>
    const input = document.getElementById('slide-image-input');
    const previewContainer = document.getElementById('image-preview-container');
    const preview = document.getElementById('image-preview');

    input?.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.classList.add('hidden');
        }
    });
</script>
@endpush
