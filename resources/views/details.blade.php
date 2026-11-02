@extends('layouts.app')

@section('content')
<!-- Page Banner Start -->
<div class="relative bg-sky-700 text-white h-56 flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('assets/images/page-banner.jpg') }}');">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative z-10 text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">{{ $product->name }}</h1>
        <ul class="flex justify-center items-center space-x-2 text-sm text-gray-200">
            <li><a href="{{ route('home.index') }}" class="hover:text-white transition">Home</a></li>
            <li>/</li>
            <li><a href="{{ route('shop.index') }}" class="hover:text-white transition">Shop</a></li>
            <li>/</li>
            <li class="text-sky-300 font-medium truncate max-w-xs">{{ $product->name }}</li>
        </ul>
    </div>
</div>
<!-- Page Banner End -->

<section class="py-12 md:py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-12">
            
            <!-- Product Images Gallery -->
            <div class="w-full lg:w-1/2 overflow-hidden">
                <div class="bg-gray-50 border rounded-xl overflow-hidden mb-4 p-4 flex items-center justify-center h-96">
                    @if($product->image)
                        <img id="main-product-img" src="{{ asset('uploads/products/' . $product->image) }}" class="max-h-full max-w-full object-contain" alt="{{ $product->name }}">
                    @else
                        <img id="main-product-img" src="https://placehold.co/400x400?text={{ urlencode($product->name) }}" class="max-h-full max-w-full object-contain" alt="{{ $product->name }}">
                    @endif
                </div>

                @php
                    $all_images = [];
                    if($product->image) {
                        $all_images[] = $product->image;
                    }
                    if($product->images) {
                        foreach(explode(',', $product->images) as $img) {
                            if(trim($img)) $all_images[] = trim($img);
                        }
                    }
                @endphp

                @if(count($all_images) > 1)
                    <div class="grid grid-cols-4 sm:grid-cols-5 gap-3">
                        @foreach($all_images as $img)
                            <div class="cursor-pointer border-2 rounded-lg overflow-hidden bg-gray-50 h-20 flex items-center justify-center p-1 hover:border-primary transition" onclick="document.getElementById('main-product-img').src = '{{ asset('uploads/products/' . $img) }}'">
                                <img src="{{ asset('uploads/products/' . $img) }}" class="max-h-full max-w-full object-contain" alt="Thumb">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Specs & Action -->
            <div class="w-full lg:w-1/2 space-y-6">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-sky-50 text-primary font-bold">
                            {{ $product->category->name ?? 'Category' }}
                        </span>
                        @if($product->brand)
                            <span class="text-xs text-gray-400">• Brand: <strong class="text-gray-700">{{ $product->brand->name }}</strong></span>
                        @endif
                    </div>
                    
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-snug">
                        {{ $product->name }}
                    </h1>
                </div>

                <div class="flex items-baseline space-x-3">
                    @if($product->sale_price)
                        <span class="text-3xl font-extrabold text-primary">${{ number_format($product->sale_price, 2) }}</span>
                        <span class="text-lg text-gray-400 line-through">${{ number_format($product->regular_price, 2) }}</span>
                        @php
                            $discount = round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100);
                        @endphp
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full uppercase">
                            Hemat {{ $discount }}%
                        </span>
                    @else
                        <span class="text-3xl font-extrabold text-gray-900">${{ number_format($product->regular_price, 2) }}</span>
                    @endif
                </div>

                <div class="border-t border-b border-gray-100 py-4">
                    <p class="text-sm text-gray-600 leading-relaxed">
                        {{ $product->short_description ?: 'No short description available.' }}
                    </p>
                </div>

                <!-- Stock & SKU -->
                <div class="space-y-1 text-sm">
                    <p class="text-gray-600">
                        <span class="font-medium text-gray-900">SKU:</span> {{ $product->SKU }}
                    </p>
                    <p class="text-gray-600">
                        <span class="font-medium text-gray-900">Availability:</span>
                        @if($product->stock_status == 'instock' && $product->quantity > 0)
                            <span class="text-green-600 font-semibold"><i class="fa-solid fa-check mr-1"></i> In Stock ({{ $product->quantity }} units available)</span>
                        @else
                            <span class="text-red-500 font-semibold"><i class="fa-solid fa-xmark mr-1"></i> Out of Stock</span>
                        @endif
                    </p>
                </div>

                <!-- Add to Cart Form -->
                <form action="{{ Route::has('cart.add') ? route('cart.add') : '#' }}" method="POST" class="pt-4 space-y-4">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <input type="hidden" name="name" value="{{ $product->name }}">
                    <input type="hidden" name="price" value="{{ $product->sale_price ?: $product->regular_price }}">

                    <div class="flex items-center gap-4">
                        <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden bg-white">
                            <button type="button" onclick="updateQty(-1)" class="px-4 py-3 text-gray-600 hover:bg-gray-100 transition focus:outline-none">
                                <i class="fa-solid fa-minus text-xs"></i>
                            </button>
                            <input type="number" id="qty-input" name="quantity" value="1" min="1" max="{{ $product->quantity }}" class="w-14 text-center border-none outline-none font-bold text-gray-800 text-sm">
                            <button type="button" onclick="updateQty(1)" class="px-4 py-3 text-gray-600 hover:bg-gray-100 transition focus:outline-none">
                                <i class="fa-solid fa-plus text-xs"></i>
                            </button>
                        </div>

                        <button type="submit" class="flex-1 bg-primary hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-lg transition shadow-md flex items-center justify-center gap-2">
                            <i class="fa-solid fa-bag-shopping"></i> Add to Cart
                        </button>
                    </div>
                </form>

                <form action="{{ route('wishlist.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <button type="submit" class="w-full py-2.5 border border-red-200 text-red-500 hover:bg-red-50 font-medium rounded-lg transition text-sm flex items-center justify-center gap-2 shadow-sm">
                        <i class="fa-regular fa-heart"></i> Add to Wishlist
                    </button>
                </form>

            </div>

        </div>

        <!-- Description Tab Section -->
        <div class="mt-16 bg-gray-50 rounded-xl p-8 border border-gray-100">
            <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                Product Description
            </h3>
            <div class="text-sm text-gray-700 leading-relaxed prose max-w-none">
                {!! nl2br(e($product->description)) !!}
            </div>
        </div>

        <!-- Related Products Section -->
        @if($rproducts->count() > 0)
            <div class="mt-16">
                <h3 class="text-2xl font-bold text-gray-900 mb-8 text-center uppercase tracking-wider">
                    Related Products
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($rproducts as $rproduct)
                        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md transition group flex flex-col justify-between">
                            <div class="relative bg-gray-50 h-56 flex items-center justify-center p-4">
                                <a href="{{ route('shop.product.details', ['slug' => $rproduct->slug]) }}" class="w-full h-full flex items-center justify-center">
                                    @if($rproduct->image)
                                        <img src="{{ asset('uploads/products/' . $rproduct->image) }}" alt="{{ $rproduct->name }}" class="max-h-full max-w-full object-contain transition duration-300 group-hover:scale-105">
                                    @else
                                        <img src="https://placehold.co/300x300?text={{ urlencode($rproduct->name) }}" alt="{{ $rproduct->name }}" class="max-h-full max-w-full object-contain">
                                    @endif
                                </a>
                            </div>
                            <div class="p-4 text-center">
                                <p class="text-xs text-gray-400 uppercase font-medium">{{ $rproduct->category->name ?? 'Store' }}</p>
                                <h4 class="font-bold text-sm text-gray-800 hover:text-primary transition line-clamp-1 mt-1">
                                    <a href="{{ route('shop.product.details', ['slug' => $rproduct->slug]) }}">
                                        {{ $rproduct->name }}
                                    </a>
                                </h4>
                                <p class="text-primary font-bold text-base mt-1">
                                    ${{ number_format($rproduct->sale_price ?: $rproduct->regular_price, 2) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</section>
@endsection

@push('scripts')
<script>
    function updateQty(amount) {
        const input = document.getElementById('qty-input');
        if (!input) return;
        let current = parseInt(input.value) || 1;
        let next = current + amount;
        if (next < 1) next = 1;
        input.value = next;
    }
</script>
@endpush

