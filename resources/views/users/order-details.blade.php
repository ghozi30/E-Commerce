@extends('layouts.app')

@section('content')
<!-- Page Banner Start -->
<div class="relative bg-sky-700 text-white h-56 flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('assets/images/page-banner.jpg') }}');">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative z-10 text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">Order #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h1>
        <ul class="flex justify-center items-center space-x-2 text-sm text-gray-200">
            <li><a href="{{ route('home.index') }}" class="hover:text-white transition">Home</a></li>
            <li>/</li>
            <li><a href="{{ route('user.orders') }}" class="hover:text-white transition">Orders</a></li>
            <li>/</li>
            <li class="text-sky-300 font-medium">Details</li>
        </ul>
    </div>
</div>
<!-- Page Banner End -->

<section class="py-12 md:py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Left Sidebar Navigation -->
            <div class="w-full lg:w-1/4">
                @include('users.account-nav')
            </div>

            <!-- Right Main Content -->
            <div class="w-full lg:w-3/4 space-y-6">
                
                @if (Session::has('status'))
                    <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm flex items-center justify-between">
                        <span>{{ Session::get('status') }}</span>
                        <button type="button" onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">&times;</button>
                    </div>
                @endif

                <div class="bg-white border rounded-xl p-6 shadow-sm">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-4 mb-6 border-b gap-4">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">
                                Order #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                            </h2>
                            <p class="text-xs text-gray-500 mt-1">Placed on {{ $order->created_at->format('F d, Y \a\t H:i') }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('user.orders') }}" class="px-4 py-2 border rounded-lg text-xs font-semibold hover:bg-gray-50 text-gray-700 transition">
                                <i class="fa-solid fa-arrow-left mr-1"></i> Back
                            </a>
                            @if ($order->status === 'ordered')
                                <form action="{{ route('user.order.cancel') }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-medium px-4 py-2 rounded-lg text-xs transition shadow-sm">
                                        Cancel Order
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Ordered Items List -->
                    <div class="overflow-x-auto mb-6">
                        <table class="w-full text-left whitespace-nowrap text-sm">
                            <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                                <tr>
                                    <th class="px-4 py-3">Product</th>
                                    <th class="px-4 py-3">Price</th>
                                    <th class="px-4 py-3 text-center">Quantity</th>
                                    <th class="px-4 py-3 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($orderItems as $item)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3.5">
                                            <div class="flex items-center gap-3">
                                                @if ($item->product && $item->product->image)
                                                    <img src="{{ asset('uploads/products/thumbnails/'.$item->product->image) }}" onerror="this.onerror=null; this.src='{{ asset('uploads/products/'.$item->product->image) }}';" class="w-12 h-12 object-cover rounded border" alt="{{ $item->product->name }}">
                                                @else
                                                    <div class="w-12 h-12 bg-gray-100 rounded border flex items-center justify-center text-gray-400">
                                                        <i class="fa-solid fa-image"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $item->product->name ?? 'Product' }}</p>
                                                    <p class="text-xs text-gray-500">SKU: {{ $item->product->SKU ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3.5 text-gray-700">${{ number_format($item->price, 2) }}</td>
                                        <td class="px-4 py-3.5 text-center font-semibold text-gray-800">{{ $item->quantity }}</td>
                                        <td class="px-4 py-3.5 text-right font-bold text-gray-900">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Details Summary Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100">
                        <!-- Shipping To -->
                        <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                            <h3 class="font-bold text-gray-800 text-sm border-b pb-2 mb-3">
                                <i class="fa-solid fa-location-dot text-primary mr-1"></i> Shipping Address
                            </h3>
                            <address class="text-sm not-italic leading-relaxed text-gray-600 space-y-1">
                                <span class="font-bold text-gray-900 block">{{ $order->name }}</span>
                                <p>{{ $order->address }}</p>
                                <p>{{ $order->locality }}</p>
                                <p>{{ $order->city }}, {{ $order->state }} - {{ $order->zip }}</p>
                                <p>{{ $order->country }}</p>
                                <p class="pt-2 text-gray-800 font-medium">
                                    <i class="fa-solid fa-phone mr-1 text-gray-400"></i> {{ $order->phone }}
                                </p>
                            </address>
                        </div>

                        <!-- Order Totals -->
                        <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                            <h3 class="font-bold text-gray-800 text-sm border-b pb-2 mb-3">
                                <i class="fa-solid fa-receipt text-primary mr-1"></i> Order Summary
                            </h3>
                            <ul class="text-sm space-y-2">
                                <li class="flex justify-between text-gray-600">
                                    <span>Subtotal:</span>
                                    <span class="font-semibold text-gray-800">${{ number_format($order->subtotal, 2) }}</span>
                                </li>
                                @if ($order->discount > 0)
                                    <li class="flex justify-between text-green-600">
                                        <span>Discount:</span>
                                        <span class="font-semibold">-${{ number_format($order->discount, 2) }}</span>
                                    </li>
                                @endif
                                <li class="flex justify-between text-gray-600">
                                    <span>Tax:</span>
                                    <span class="font-semibold text-gray-800">${{ number_format($order->tax, 2) }}</span>
                                </li>
                                <li class="flex justify-between text-gray-900 border-t pt-2 font-bold text-base">
                                    <span>Total:</span>
                                    <span class="text-primary">${{ number_format($order->total, 2) }}</span>
                                </li>
                                <li class="flex justify-between text-gray-600 pt-2 border-t text-xs">
                                    <span>Status:</span>
                                    <span class="font-semibold uppercase {{ $order->status === 'delivered' ? 'text-green-600' : ($order->status === 'canceled' ? 'text-red-600' : 'text-blue-600') }}">
                                        {{ $order->status }}
                                    </span>
                                </li>
                                <li class="flex justify-between text-gray-600 text-xs">
                                    <span>Payment Mode:</span>
                                    <span class="font-semibold uppercase text-gray-800">{{ $transaction->mode ?? 'COD' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>
@endsection

