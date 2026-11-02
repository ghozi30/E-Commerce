@extends('layouts.app')

@section('content')
<!-- Page Banner Start -->
<div class="relative bg-sky-700 text-white h-56 flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('assets/images/page-banner.jpg') }}');">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative z-10 text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">Shop Catalog</h1>
        <ul class="flex justify-center items-center space-x-2 text-sm text-gray-200">
            <li><a href="{{ route('home.index') }}" class="hover:text-white transition">Home</a></li>
            <li>/</li>
            <li class="text-sky-300 font-medium">Shop</li>
        </ul>
    </div>
</div>
<!-- Page Banner End -->

<div class="container mx-auto px-4 py-12 md:py-16">
    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Left Filter Sidebar -->
        <aside class="w-full lg:w-1/4 order-2 lg:order-1 space-y-6">
            
            <!-- Search Widget -->
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100 shadow-sm">
                <form action="{{ route('shop.index') }}" method="GET" class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products..." class="w-full border border-gray-200 bg-white p-3 rounded-lg text-sm focus:outline-none focus:border-primary pr-10">
                    <button type="submit" class="absolute right-3 top-3 text-gray-400 hover:text-primary">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- Categories Filter -->
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100 shadow-sm">
                <h4 class="font-bold text-gray-800 text-base mb-4 flex items-center justify-between">
                    <span>Categories</span>
                    <i class="fa-solid fa-layer-group text-primary text-sm"></i>
                </h4>
                <ul class="space-y-2.5 max-h-60 overflow-y-auto pr-2 no-scrollbar text-sm">
                    @php
                        $selected_categories = explode(',', (string) $f_categories);
                    @endphp
                    @foreach($categories as $category)
                        <li>
                            <label class="flex items-center justify-between cursor-pointer hover:text-primary group">
                                <div class="flex items-center gap-2.5">
                                    <input type="checkbox" name="categories" value="{{ $category->id }}" class="category-filter rounded border-gray-300 text-primary focus:ring-primary" {{ in_array($category->id, $selected_categories) ? 'checked' : '' }} onchange="filterProducts()">
                                    <span class="text-gray-700 group-hover:text-primary">{{ $category->name }}</span>
                                </div>
                                <span class="text-xs text-gray-400 font-medium">({{ $category->products_count ?? 0 }})</span>
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Brands Filter -->
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100 shadow-sm">
                <h4 class="font-bold text-gray-800 text-base mb-4 flex items-center justify-between">
                    <span>Brands</span>
                    <i class="fa-solid fa-tag text-primary text-sm"></i>
                </h4>
                <ul class="space-y-2.5 max-h-60 overflow-y-auto pr-2 no-scrollbar text-sm">
                    @php
                        $selected_brands = explode(',', (string) $f_brands);
                    @endphp
                    @foreach($brands as $brand)
                        <li>
                            <label class="flex items-center justify-between cursor-pointer hover:text-primary group">
                                <div class="flex items-center gap-2.5">
                                    <input type="checkbox" name="brands" value="{{ $brand->id }}" class="brand-filter rounded border-gray-300 text-primary focus:ring-primary" {{ in_array($brand->id, $selected_brands) ? 'checked' : '' }} onchange="filterProducts()">
                                    <span class="text-gray-700 group-hover:text-primary">{{ $brand->name }}</span>
                                </div>
                                <span class="text-xs text-gray-400 font-medium">({{ $brand->products_count ?? 0 }})</span>
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Price Filter -->
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100 shadow-sm">
                <h4 class="font-bold text-gray-800 text-base mb-4 flex items-center justify-between">
                    <span>Filter by Price</span>
                    <i class="fa-solid fa-dollar-sign text-primary text-sm"></i>
                </h4>
                <div class="space-y-4">
                    <div class="flex items-center justify-between gap-3 text-sm">
                        <div class="w-1/2">
                            <label class="text-xs text-gray-500 mb-1 block">Min ($)</label>
                            <input type="number" id="min-price-input" value="{{ $min_price }}" class="w-full border bg-white p-2 rounded text-sm text-gray-700 outline-none focus:border-primary">
                        </div>
                        <div class="w-1/2">
                            <label class="text-xs text-gray-500 mb-1 block">Max ($)</label>
                            <input type="number" id="max-price-input" value="{{ $max_price }}" class="w-full border bg-white p-2 rounded text-sm text-gray-700 outline-none focus:border-primary">
                        </div>
                    </div>
                    <button type="button" onclick="filterProducts()" class="w-full bg-primary text-white py-2 rounded-lg text-sm font-medium hover:bg-blue-600 transition shadow-sm">
                        Apply Price Filter
                    </button>
                    @if(request()->hasAny(['categories', 'brands', 'min', 'max', 'order', 'q']))
                        <a href="{{ route('shop.index') }}" class="block text-center text-xs text-red-500 hover:underline pt-2">
                            <i class="fa-solid fa-rotate-left mr-1"></i> Reset All Filters
                        </a>
                    @endif
                </div>
            </div>

        </aside>

        <!-- Right Products Grid -->
        <div class="w-full lg:w-3/4 order-1 lg:order-2">
            
            <!-- Filter Bar / Sorting -->
            <div class="flex flex-col sm:flex-row justify-between items-center bg-white border border-gray-100 p-4 rounded-xl mb-8 shadow-sm gap-4">
                <p class="text-sm text-gray-600">
                    Showing <span class="font-bold text-gray-900">{{ $products->firstItem() ?? 0 }}</span> - <span class="font-bold text-gray-900">{{ $products->lastItem() ?? 0 }}</span> of <span class="font-bold text-primary">{{ $products->total() }}</span> Results
                </p>
                
                <div class="flex items-center gap-4 flex-wrap">
                    <!-- Per Page Size -->
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500 font-medium">Show:</span>
                        <select id="pagesize" onchange="filterProducts()" class="border border-gray-200 rounded-lg p-1.5 text-xs focus:outline-none focus:border-primary bg-white text-gray-700">
                            <option value="12" {{ $size == 12 ? 'selected' : '' }}>12 Items</option>
                            <option value="24" {{ $size == 24 ? 'selected' : '' }}>24 Items</option>
                            <option value="48" {{ $size == 48 ? 'selected' : '' }}>48 Items</option>
                        </select>
                    </div>

                    <!-- Sort By -->
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500 font-medium">Sort By:</span>
                        <select id="orderby" onchange="filterProducts()" class="border border-gray-200 rounded-lg p-1.5 text-xs focus:outline-none focus:border-primary bg-white text-gray-700">
                            <option value="-1" {{ $order == -1 ? 'selected' : '' }}>Default</option>
                            <option value="1" {{ $order == 1 ? 'selected' : '' }}>Newest</option>
                            <option value="2" {{ $order == 2 ? 'selected' : '' }}>Oldest</option>
                            <option value="3" {{ $order == 3 ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="4" {{ $order == 4 ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Products Grid View -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @forelse($products as $product)
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md transition-shadow group flex flex-col justify-between">
                        <div>
                            <div class="relative overflow-hidden bg-gray-50 h-64 flex items-center justify-center p-4">
                                <a href="{{ route('shop.product.details', ['slug' => $product->slug]) }}" class="w-full h-full flex items-center justify-center">
                                    @if($product->image)
                                        <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}" class="max-h-full max-w-full object-contain transition duration-500 group-hover:scale-105" onerror="this.onerror=null; this.src='https://placehold.co/300x300?text=Product';">
                                    @else
                                        <img src="https://placehold.co/300x300?text={{ urlencode($product->name) }}" alt="{{ $product->name }}" class="max-h-full max-w-full object-contain">
                                    @endif
                                </a>

                                @if($product->sale_price)
                                    @php
                                        $discount = round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100);
                                    @endphp
                                    <span class="absolute top-3 left-3 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                                        -{{ $discount }}%
                                    </span>
                                @endif

                                @if($product->featured)
                                    <span class="absolute top-3 right-3 bg-amber-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                                        Featured
                                    </span>
                                @endif

                                <div class="absolute bottom-3 left-0 right-0 flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <a href="{{ route('shop.product.details', ['slug' => $product->slug]) }}" class="w-9 h-9 bg-white text-gray-700 rounded-full shadow hover:bg-primary hover:text-white flex items-center justify-center transition" title="Quick View">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="p-5 text-left">
                                <p class="text-xs text-gray-400 uppercase font-medium mb-1">
                                    {{ $product->category->name ?? 'Store' }}
                                </p>
                                <h3 class="font-bold text-gray-800 text-base hover:text-primary transition line-clamp-1">
                                    <a href="{{ route('shop.product.details', ['slug' => $product->slug]) }}">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                                    {{ $product->short_description }}
                                </p>
                            </div>
                        </div>

                        <div class="px-5 pb-5 pt-2 border-t border-gray-50 flex items-center justify-between">
                            <div>
                                @if($product->sale_price)
                                    <span class="text-primary font-bold text-lg">${{ number_format($product->sale_price, 2) }}</span>
                                    <span class="text-xs text-gray-400 line-through ml-1">${{ number_format($product->regular_price, 2) }}</span>
                                @else
                                    <span class="text-gray-900 font-bold text-lg">${{ number_format($product->regular_price, 2) }}</span>
                                @endif
                            </div>
                            
                            <a href="{{ route('shop.product.details', ['slug' => $product->slug]) }}" class="px-3 py-1.5 bg-sky-50 text-primary hover:bg-primary hover:text-white rounded-lg text-xs font-semibold transition">
                                View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center bg-white rounded-xl border border-gray-100">
                        <i class="fa-solid fa-box-open text-4xl text-gray-300 mb-3"></i>
                        <h3 class="text-lg font-bold text-gray-700">No products match your filter</h3>
                        <p class="text-sm text-gray-500 mt-1">Try adjusting your category, brand or price filters.</p>
                        <a href="{{ route('shop.index') }}" class="inline-block mt-4 px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-blue-600 transition">
                            Clear Filters
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif

        </div>

    </div>
</div>

<form id="filter-form" action="{{ route('shop.index') }}" method="GET" class="hidden">
    <input type="hidden" name="size" id="form-size" value="{{ $size }}">
    <input type="hidden" name="order" id="form-order" value="{{ $order }}">
    <input type="hidden" name="categories" id="form-categories" value="{{ $f_categories }}">
    <input type="hidden" name="brands" id="form-brands" value="{{ $f_brands }}">
    <input type="hidden" name="min" id="form-min" value="{{ $min_price }}">
    <input type="hidden" name="max" id="form-max" value="{{ $max_price }}">
    @if(request('q'))
        <input type="hidden" name="q" value="{{ request('q') }}">
    @endif
</form>
@endsection

@push('scripts')
<script>
    function filterProducts() {
        const size = document.getElementById('pagesize').value;
        const order = document.getElementById('orderby').value;
        const minPrice = document.getElementById('min-price-input').value;
        const maxPrice = document.getElementById('max-price-input').value;

        // Categories checkboxes
        const selectedCategories = [];
        document.querySelectorAll('.category-filter:checked').forEach(cb => {
            selectedCategories.push(cb.value);
        });

        // Brands checkboxes
        const selectedBrands = [];
        document.querySelectorAll('.brand-filter:checked').forEach(cb => {
            selectedBrands.push(cb.value);
        });

        document.getElementById('form-size').value = size;
        document.getElementById('form-order').value = order;
        document.getElementById('form-categories').value = selectedCategories.join(',');
        document.getElementById('form-brands').value = selectedBrands.join(',');
        document.getElementById('form-min').value = minPrice;
        document.getElementById('form-max').value = maxPrice;

        document.getElementById('filter-form').submit();
    }
</script>
@endpush
