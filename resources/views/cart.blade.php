@extends('layouts.app')

@section('content')
<!-- Page Banner Start -->
<div class="relative bg-sky-700 text-white h-56 flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('assets/images/page-banner.jpg') }}');">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative z-10 text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">Shopping Cart</h1>
        <ul class="flex justify-center items-center space-x-2 text-sm text-gray-200">
            <li><a href="{{ route('home.index') }}" class="hover:text-white transition">Home</a></li>
            <li>/</li>
            <li><a href="{{ route('shop.index') }}" class="hover:text-white transition">Shop</a></li>
            <li>/</li>
            <li class="text-sky-300 font-medium">Cart</li>
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
            <div class="flex flex-col lg:flex-row gap-8">
                
                <!-- Cart Items Table -->
                <div class="w-full lg:w-2/3">
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-6">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left whitespace-nowrap">
                                <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-semibold border-b">
                                    <tr>
                                        <th class="py-4 px-6">Product</th>
                                        <th class="py-4 px-6">Price</th>
                                        <th class="py-4 px-6">Quantity</th>
                                        <th class="py-4 px-6">Subtotal</th>
                                        <th class="py-4 px-6 text-right">Action</th>
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

                                            <td class="py-4 px-6 font-semibold text-gray-700">
                                                ${{ number_format($item->price, 2) }}
                                            </td>

                                            <td class="py-4 px-6">
                                                <div class="flex items-center border border-gray-200 rounded-lg w-max bg-white overflow-hidden shadow-sm">
                                                    <form action="{{ route('cart.decrease.qty', ['rowId' => $item->rowId]) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="px-3 py-1 hover:bg-gray-100 text-gray-600 transition focus:outline-none">
                                                            <i class="fa-solid fa-minus text-xs"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    <span class="w-10 text-center font-bold text-gray-800 text-xs">
                                                        {{ $item->qty }}
                                                    </span>

                                                    <form action="{{ route('cart.increase.qty', ['rowId' => $item->rowId]) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="px-3 py-1 hover:bg-gray-100 text-gray-600 transition focus:outline-none">
                                                            <i class="fa-solid fa-plus text-xs"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>

                                            <td class="py-4 px-6 font-bold text-primary">
                                                ${{ number_format($item->subtotal(), 2) }}
                                            </td>

                                            <td class="py-4 px-6 text-right">
                                                <form action="{{ route('cart.remove', ['rowId' => $item->rowId]) }}" method="POST" onsubmit="return confirm('Remove this item from your cart?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-8 h-8 rounded-full hover:bg-red-50 text-gray-400 hover:text-red-500 transition inline-flex items-center justify-center" title="Remove Item">
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

                            <form action="{{ route('cart.empty') }}" method="POST" onsubmit="return confirm('Clear all items from your cart?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-2 text-sm text-red-500 hover:text-red-700 font-medium transition">
                                    <i class="fa-solid fa-trash"></i>
                                    <span>Clear Cart</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="w-full lg:w-1/3">
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-6">
                        <h3 class="font-bold text-gray-800 text-lg border-b pb-3">Order Summary</h3>

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span class="font-semibold text-gray-800">${{ number_format(Cart::instance('cart')->subtotal(), 2) }}</span>
                            </div>

                            <div class="flex justify-between text-gray-600">
                                <span>Estimated Tax (10%)</span>
                                <span class="font-semibold text-gray-800">${{ number_format(Cart::instance('cart')->tax(), 2) }}</span>
                            </div>

                            <div class="flex justify-between text-gray-600">
                                <span>Shipping</span>
                                <span class="font-semibold text-green-600">FREE</span>
                            </div>

                            <div class="border-t pt-3 flex justify-between text-base font-bold text-gray-900">
                                <span>Total</span>
                                <span class="text-primary text-xl">${{ number_format(Cart::instance('cart')->total(), 2) }}</span>
                            </div>
                        </div>

                        <a href="{{ Route::has('cart.checkout') ? route('cart.checkout') : '#' }}" class="block text-center w-full bg-primary hover:bg-blue-600 text-white font-medium py-3 rounded-lg transition shadow-md">
                            Proceed to Checkout <i class="fa-solid fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

            </div>
        @else
            <!-- Empty Cart State -->
            <div class="text-center py-16 bg-white rounded-xl border border-gray-100 max-w-xl mx-auto shadow-sm p-8">
                <div class="w-24 h-24 bg-sky-50 rounded-full flex items-center justify-center mx-auto mb-6 text-primary">
                    <i class="fa-solid fa-bag-shopping text-4xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Your Cart is Empty</h2>
                <p class="text-sm text-gray-500 mb-6 max-w-sm mx-auto">
                    Looks like you haven't added anything to your cart yet. Browse our products and discover great deals!
                </p>
                <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 bg-primary text-white px-8 py-3 rounded-lg hover:bg-blue-600 transition font-medium shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Start Shopping</span>
                </a>
            </div>
        @endif

    </div>
</section>
@endsection
