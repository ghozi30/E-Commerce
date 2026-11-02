<x-app-layout>
    <!-- Hero Slider -->
    <section class="relative bg-gray-50 overflow-hidden">
        <div class="swiper-container main-slider h-[500px] lg:h-[600px]">
            <div class="swiper-wrapper">
                @if(isset($slides) && $slides->count() > 0)
                    @foreach($slides as $slide)
                        <div class="swiper-slide flex items-center justify-center">
                            <div class="container mx-auto px-4 grid lg:grid-cols-2 gap-8 items-center h-full">
                                <div class="text-center lg:text-left space-y-4 animate-fadeIn">
                                    <span class="text-xs uppercase tracking-widest font-bold text-primary bg-sky-50 px-3 py-1 rounded-full border border-sky-100">{{ $slide->tagline }}</span>
                                    <h2 class="text-4xl lg:text-6xl font-bold text-gray-800 leading-tight">{{ $slide->title }}</h2>
                                    <p class="text-lg text-gray-600">{{ $slide->subtitle }}</p>
                                    <a href="{{ $slide->link }}" class="inline-block bg-primary text-white px-8 py-3 rounded-lg hover:bg-blue-600 transition shadow-lg mt-4 font-medium">
                                        Shop Now
                                    </a>
                                </div>
                                <div class="hidden lg:block text-center">
                                    <img src="{{ asset('uploads/slides/'.$slide->image) }}" onerror="this.onerror=null; this.src='{{ asset('assets/images/slider/'.$slide->image) }}';" alt="{{ $slide->title }}" class="mx-auto max-h-[420px] object-contain" />
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="swiper-slide flex items-center justify-center">
                        <div class="container mx-auto px-4 grid lg:grid-cols-2 gap-8 items-center h-full">
                            <div class="text-center lg:text-left space-y-4 animate-fadeIn">
                                <h2 class="text-4xl lg:text-6xl font-bold text-gray-800 leading-tight">New Stylish <br /> Decor Furniture</h2>
                                <p class="text-lg text-gray-600">Unique Furniture Style Design for Your Family</p>
                                <a href="{{ route('shop.index') }}" class="inline-block bg-primary text-white px-8 py-3 rounded-lg hover:bg-blue-600 transition shadow-lg mt-4 font-medium">
                                    Shop Catalog
                                </a>
                            </div>
                            <div class="hidden lg:block">
                                <img src="{{ asset('assets/images/slider/slider-item-1.png') }}" alt="Furniture" class="mx-auto" />
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <!-- Popular Categories -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl font-bold mb-10 text-center uppercase tracking-widest text-gray-800">
                Popular Categories
            </h2>

            <div class="swiper-container category-slider overflow-hidden">
                <div class="swiper-wrapper pb-10">
                    @forelse($categories as $category)
                        <div class="swiper-slide text-center group">
                            <a href="{{ route('shop.index', ['categories' => $category->id]) }}" class="block bg-white rounded-2xl p-6 transition-all duration-300 border border-transparent hover:border-gray-100 hover:shadow-2xl hover:-translate-y-2">
                                @if($category->image)
                                    <img src="{{ asset('uploads/categories/' . $category->image) }}" class="mx-auto w-24 h-24 object-contain opacity-85 group-hover:opacity-100 transition-opacity duration-300" alt="{{ $category->name }}" onerror="this.onerror=null; this.src='https://placehold.co/96x96?text=C';">
                                @else
                                    <div class="w-24 h-24 mx-auto flex items-center justify-center bg-sky-50 text-primary font-bold text-2xl rounded-xl">
                                        {{ strtoupper(substr($category->name, 0, 1)) }}
                                    </div>
                                @endif
                            </a>
                            <h4 class="mt-4 font-semibold text-gray-700 group-hover:text-primary transition-colors uppercase text-xs tracking-widest">
                                {{ $category->name }}
                            </h4>
                        </div>
                    @empty
                        <div class="w-full text-center py-6 text-gray-400">No categories found.</div>
                    @endforelse
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <!-- Features / Perks -->
    <section class="py-10 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6 border rounded-xl hover:shadow-md transition">
                    <img src="{{ asset('assets/images/icon/icon-1.png') }}" alt="Free Shipping" class="mx-auto mb-4 h-14" />
                    <h3 class="text-lg font-bold mb-2">Free Shipping</h3>
                    <p class="text-sm text-gray-500">Free delivery on all eligible orders over $150 with fast tracking.</p>
                </div>
                <div class="text-center p-6 border rounded-xl hover:shadow-md transition">
                    <img src="{{ asset('assets/images/icon/icon-2.png') }}" alt="Safe Payment" class="mx-auto mb-4 h-14" />
                    <h3 class="text-lg font-bold mb-2">Safe Payment</h3>
                    <p class="text-sm text-gray-500">100% secure payment gateway with SSL encryption and fraud prevention.</p>
                </div>
                <div class="text-center p-6 border rounded-xl hover:shadow-md transition">
                    <img src="{{ asset('assets/images/icon/icon-3.png') }}" alt="Online Support" class="mx-auto mb-4 h-14" />
                    <h3 class="text-lg font-bold mb-2">24/7 Dedicated Support</h3>
                    <p class="text-sm text-gray-500">Our customer care team is always available to help you anytime.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- New Products -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-10 text-gray-800">New Arrivals</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($latest_products as $product)
                    <div class="group relative bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between">
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
                                    <span class="absolute top-3 left-3 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">
                                        Sale
                                    </span>
                                @endif

                                <div class="absolute top-4 right-4 flex flex-col space-y-2 opacity-0 group-hover:opacity-100 transition duration-300 transform translate-x-4 group-hover:translate-x-0">
                                    <a href="{{ route('shop.product.details', ['slug' => $product->slug]) }}" class="w-9 h-9 bg-white rounded-full shadow hover:bg-primary hover:text-white flex items-center justify-center transition" title="Quick View">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="p-4 text-center">
                                <p class="text-xs text-gray-400 uppercase font-medium mb-1">{{ $product->category->name ?? 'Shop' }}</p>
                                <h4 class="text-base font-semibold text-gray-800 hover:text-primary transition line-clamp-1">
                                    <a href="{{ route('shop.product.details', ['slug' => $product->slug]) }}">{{ $product->name }}</a>
                                </h4>
                                <div class="flex justify-center items-center space-x-2 mt-2">
                                    @if($product->sale_price)
                                        <span class="text-primary font-bold text-base">${{ number_format($product->sale_price, 2) }}</span>
                                        <span class="text-gray-400 line-through text-xs">${{ number_format($product->regular_price, 2) }}</span>
                                    @else
                                        <span class="text-gray-900 font-bold text-base">${{ number_format($product->regular_price, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="px-4 pb-4">
                            <a href="{{ route('shop.product.details', ['slug' => $product->slug]) }}" class="block text-center w-full py-2 bg-sky-50 text-primary hover:bg-primary hover:text-white rounded-lg text-xs font-semibold transition">
                                View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-gray-400">
                        No products available yet.
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 bg-primary text-white px-8 py-3 rounded-lg hover:bg-blue-600 font-medium transition shadow-sm">
                    <span>Explore All Products</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Promotional Banners -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-8">
                <div class="relative group overflow-hidden rounded-xl">
                    <img src="{{ asset('assets/images/banner/banner-05.jpg') }}" alt="Banner" class="w-full transition duration-500 group-hover:scale-105" />
                    <div class="absolute inset-0 bg-black/20 flex flex-col justify-center items-start p-8">
                        <span class="text-xs font-bold uppercase tracking-wider text-sky-200 mb-2">High Quality</span>
                        <h3 class="text-2xl font-bold text-white mb-4">Modern Living Room <br> Furniture</h3>
                        <a href="{{ route('shop.index') }}" class="bg-primary text-white px-6 py-2.5 rounded-lg hover:bg-blue-600 transition text-sm font-medium shadow">Shop Now</a>
                    </div>
                </div>
                <div class="relative group overflow-hidden rounded-xl">
                    <img src="{{ asset('assets/images/banner/banner-06.jpg') }}" alt="Banner" class="w-full transition duration-500 group-hover:scale-105" />
                    <div class="absolute inset-0 bg-black/20 flex flex-col justify-center items-start p-8">
                        <span class="text-xs font-bold uppercase tracking-wider text-sky-200 mb-2">Best Deals</span>
                        <h3 class="text-2xl font-bold text-white mb-4">Designer Bedroom <br> Furniture</h3>
                        <a href="{{ route('shop.index') }}" class="bg-primary text-white px-6 py-2.5 rounded-lg hover:bg-blue-600 transition text-sm font-medium shadow">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products Slider -->
    @if($featured_products->count() > 0)
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-end mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Featured Products</h2>
                        <p class="text-sm text-gray-500">Hand-picked premium selections from our collection</p>
                    </div>
                    <a href="{{ route('shop.index') }}" class="text-sm text-primary font-semibold hover:underline">
                        See All
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($featured_products as $fproduct)
                        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md transition p-4 text-center">
                            <div class="overflow-hidden rounded-lg bg-gray-50 mb-4 relative h-48 flex items-center justify-center p-2">
                                <a href="{{ route('shop.product.details', ['slug' => $fproduct->slug]) }}" class="w-full h-full flex items-center justify-center">
                                    @if($fproduct->image)
                                        <img src="{{ asset('uploads/products/' . $fproduct->image) }}" class="max-h-full max-w-full object-contain group-hover:scale-105 transition duration-500" alt="{{ $fproduct->name }}">
                                    @else
                                        <img src="https://placehold.co/300x300?text={{ urlencode($fproduct->name) }}" class="max-h-full max-w-full object-contain" alt="{{ $fproduct->name }}">
                                    @endif
                                </a>
                            </div>
                            <h4 class="font-medium text-sm text-gray-800 hover:text-primary transition line-clamp-1">
                                <a href="{{ route('shop.product.details', ['slug' => $fproduct->slug]) }}">{{ $fproduct->name }}</a>
                            </h4>
                            <p class="text-primary font-bold mt-1 text-base">
                                ${{ number_format($fproduct->sale_price ?: $fproduct->regular_price, 2) }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-app-layout>
