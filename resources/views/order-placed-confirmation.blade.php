@extends('layouts.app')

@section('content')
<!-- Page Banner -->
<div class="relative bg-sky-700 text-white h-48 md:h-64 flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('assets/images/page-banner.jpg') }}');">
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
    <div class="relative z-10 text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">Order Confirmed</h1>
        <ul class="flex justify-center space-x-2 text-sm">
            <li><a href="{{ route('home.index') }}" class="hover:text-primary transition">Home</a></li>
            <li>/</li>
            <li class="text-primary font-medium">Order Placed</li>
        </ul>
    </div>
</div>

<section class="py-12 md:py-16 bg-white">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-gray-50 p-8 md:p-12 rounded-2xl border border-gray-200 text-center shadow-sm">
            
            <div class="mb-6 inline-flex items-center justify-center w-20 h-20 bg-green-100 text-green-600 rounded-full shadow-inner">
                <i class="fa-solid fa-check text-3xl"></i>
            </div>

            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Thank You for Your Order!</h2>
            <p class="text-base md:text-lg text-gray-600 mb-8">
                Your order has been placed successfully. Your order ID is 
                <span class="font-bold text-primary">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left mb-10">
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <h3 class="font-bold text-gray-800 border-b pb-3 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-receipt text-primary"></i> Order Details
                    </h3>
                    <ul class="space-y-2.5 text-sm">
                        <li class="flex justify-between text-gray-600">
                            <span>Order Date:</span>
                            <span class="font-medium text-gray-800">{{ $order->created_at->format('F d, Y') }}</span>
                        </li>
                        <li class="flex justify-between text-gray-600">
                            <span>Subtotal:</span>
                            <span class="font-medium text-gray-800">${{ number_format($order->subtotal, 2) }}</span>
                        </li>
                        @if($order->discount > 0)
                            <li class="flex justify-between text-green-600">
                                <span>Discount:</span>
                                <span class="font-semibold">-${{ number_format($order->discount, 2) }}</span>
                            </li>
                        @endif
                        <li class="flex justify-between text-gray-600">
                            <span>Tax:</span>
                            <span class="font-medium text-gray-800">${{ number_format($order->tax, 2) }}</span>
                        </li>
                        <li class="flex justify-between text-gray-600 border-t pt-2">
                            <span class="font-bold text-gray-900">Total Paid / Due:</span>
                            <span class="font-bold text-primary text-base">${{ number_format($order->total, 2) }}</span>
                        </li>
                        <li class="flex justify-between text-gray-600">
                            <span>Payment Mode:</span>
                            <span class="font-semibold uppercase text-gray-700">{{ $order->transaction->mode ?? 'COD' }}</span>
                        </li>
                        <li class="flex justify-between text-gray-600">
                            <span>Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 uppercase">
                                {{ $order->status }}
                            </span>
                        </li>
                    </ul>
                </div>

                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <h3 class="font-bold text-gray-800 border-b pb-3 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-location-dot text-primary"></i> Shipping Address
                    </h3>
                    <address class="text-sm not-italic leading-relaxed text-gray-600 space-y-1">
                        <span class="font-bold text-gray-900 block text-base">{{ $order->name }}</span>
                        <p>{{ $order->address }}</p>
                        <p>{{ $order->locality }}</p>
                        <p>{{ $order->city }}, {{ $order->state }} - {{ $order->zip }}</p>
                        <p>{{ $order->country }}</p>
                        <p class="pt-2 text-gray-800 font-medium"><i class="fa-solid fa-phone mr-1 text-gray-400"></i> {{ $order->phone }}</p>
                    </address>
                </div>
            </div>

            <!-- Ordered Items Preview -->
            @if($order->orderItems && $order->orderItems->count() > 0)
                <div class="bg-white rounded-xl border border-gray-200 p-6 mb-8 text-left shadow-sm">
                    <h3 class="font-bold text-gray-800 border-b pb-3 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-box-open text-primary"></i> Ordered Items ({{ $order->orderItems->count() }})
                    </h3>
                    <div class="divide-y divide-gray-100">
                        @foreach($order->orderItems as $item)
                            <div class="py-3 flex items-center justify-between text-sm">
                                <div class="flex items-center gap-3">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ asset('uploads/products/thumbnails/'.$item->product->image) }}" onerror="this.onerror=null; this.src='{{ asset('uploads/products/'.$item->product->image) }}';" class="w-10 h-10 object-cover rounded border" alt="{{ $item->product->name }}">
                                    @else
                                        <div class="w-10 h-10 bg-gray-100 rounded border flex items-center justify-center text-gray-400">
                                            <i class="fa-solid fa-image"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <span class="font-medium text-gray-800 block">{{ $item->product->name ?? 'Product' }}</span>
                                        <span class="text-xs text-gray-500">Qty: {{ $item->quantity }} &times; ${{ number_format($item->price, 2) }}</span>
                                    </div>
                                </div>
                                <span class="font-semibold text-gray-800">${{ number_format($item->price * $item->quantity, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('shop.index') }}" class="bg-primary text-white px-8 py-3 rounded-lg font-bold hover:bg-blue-600 transition shadow-md">
                    Continue Shopping
                </a>
                <a href="{{ route('user.index') }}" class="bg-white border border-gray-300 text-gray-700 px-8 py-3 rounded-lg font-bold hover:bg-gray-100 transition">
                    My Account Dashboard
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
