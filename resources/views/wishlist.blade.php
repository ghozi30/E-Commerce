@extends('layouts.app')

@section('content')
<!-- Page Banner Start -->
<div class="relative bg-sky-700 text-white h-56 flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('assets/images/page-banner.jpg') }}');">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative z-10 text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">My Wishlist</h1>
        <ul class="flex justify-center items-center space-x-2 text-sm text-gray-200">
            <li><a href="{{ route('home.index') }}" class="hover:text-white transition">Home</a></li>
            <li>/</li>
            <li><a href="{{ route('shop.index') }}" class="hover:text-white transition">Shop</a></li>
            <li>/</li>
            <li class="text-sky-300 font-medium">Wishlist</li>
        </ul>
    </div>
</div>
<!-- Page Banner End -->

<section class="py-12 md:py-16 bg-white">
    <div class="container mx-auto px-4">
        
        @if(Session::has('status'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-green-500"></i>
                    <span>{{ Session::get('status') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if($items->count() > 0)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-semibold border-b">
                            <tr>
                                <th class="py-4 px-6">Product</th>
                                <th class="py-4 px-6">Price</th>
                                <th class="py-4 px-6">Stock Status</th>
                                <th class="py-4 px-6 text-center">Action</th>
                                <th class="py-4 px-6 text-right">Remove</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @foreach($items as $item)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-16 h-16 rounded-lg border bg-gray-50 flex items-center justify-center overflow-hidden flex-shrink-0">
                                                @if($item->model && $item->model->image)
                                                    <img src="{{ asset('uploads/products/' . $item->model->image) }}" alt="{{ $item->name }}" class="max-h-full max-w-full object-contain" onerror="this.onerror=null; this.src='https://placehold.co/60x60?text=P';">
                                                @else
                                                    <img src="https://placehold.co/60x60?text={{ urlencode($item->name) }}" alt="{{ $item->name }}" class="max-h-full max-w-full object-contain">
                                                @endif
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-800 hover:text-primary transition">
                                                    @if($item->model)
                                                        <a href="{{ route('shop.product.details', ['slug' => $item->model->slug]) }}">
                                                            {{ $item->name }}
                                                        </a>
                                                    @else
                                                        {{ $item->name }}
                                                    @endif
                                                </h4>
                                                <p class="text-xs text-gray-400 mt-0.5">SKU: {{ $item->model->SKU ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="py-4 px-6 font-bold text-primary">
                                        ${{ number_format($item->price, 2) }}
                                    </td>

                                    <td class="py-4 px-6">
                                        @if($item->model && $item->model->stock_status == 'instock')
                                            <span class="bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-xs font-semibold">
                                                In Stock
                                            </span>
                                        @else
                                            <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-xs font-semibold">
                                                Out of Stock
                                            </span>
                                        @endif
                                    </td>

                                    <td class="py-4 px-6 text-center">
                                        <form action="{{ route('wishlist.move.to.cart', ['rowId' => $item->rowId]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-primary hover:bg-blue-600 text-white rounded-lg text-xs font-semibold transition shadow-sm inline-flex items-center gap-1.5">
                                                <i class="fa-solid fa-cart-plus"></i> Move to Cart
                                            </button>
                                        </form>
                                    </td>

                                    <td class="py-4 px-6 text-right">
                                        <form action="{{ route('wishlist.remove', ['rowId' => $item->rowId]) }}" method="POST" onsubmit="return confirm('Remove from wishlist?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-full hover:bg-red-50 text-gray-400 hover:text-red-500 transition inline-flex items-center justify-center" title="Remove">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 bg-gray-50 border-t flex flex-wrap items-center justify-between gap-4">
                    <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-primary font-medium transition">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Continue Shopping</span>
                    </a>

                    <form action="{{ route('wishlist.empty') }}" method="POST" onsubmit="return confirm('Clear all items from your wishlist?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-2 text-sm text-red-500 hover:text-red-700 font-medium transition">
                            <i class="fa-solid fa-trash"></i>
                            <span>Clear Wishlist</span>
                        </button>
                    </form>
                </div>
            </div>
        @else
            <!-- Empty Wishlist State -->
            <div class="text-center py-16 bg-white rounded-xl border border-gray-100 max-w-xl mx-auto shadow-sm p-8">
                <div class="w-24 h-24 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6 text-red-500">
                    <i class="fa-regular fa-heart text-4xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Your Wishlist is Empty</h2>
                <p class="text-sm text-gray-500 mb-6 max-w-sm mx-auto">
                    Save your favorite products to your wishlist so you can easily find and purchase them later.
                </p>
                <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 bg-primary text-white px-8 py-3 rounded-lg hover:bg-blue-600 transition font-medium shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Discover Products</span>
                </a>
            </div>
        @endif

    </div>
</section>
@endsection

