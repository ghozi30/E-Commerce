@extends('layouts.app')

@section('content')
<!-- Page Banner -->
<div class="relative bg-sky-700 text-white h-48 md:h-64 flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('assets/images/page-banner.jpg') }}');">
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
    <div class="relative z-10 text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">Checkout</h1>
        <ul class="flex justify-center space-x-2 text-sm">
            <li><a href="{{ route('home.index') }}" class="hover:text-primary transition">Home</a></li>
            <li>/</li>
            <li><a href="{{ route('cart.index') }}" class="hover:text-primary transition">Cart</a></li>
            <li>/</li>
            <li class="text-primary font-medium">Checkout</li>
        </ul>
    </div>
</div>

<section class="py-12 md:py-16 bg-white">
    <div class="container mx-auto px-4">
        @if ($errors->any())
            <div class="mb-6 max-w-4xl mx-auto p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('cart.place.an.order') }}" method="POST">
            @csrf
            <div class="flex flex-col lg:flex-row gap-12">
                <!-- Shipping Details -->
                <div class="w-full lg:w-7/12">
                    <div class="bg-gray-50 p-6 md:p-8 rounded-xl border border-gray-200 shadow-sm">
                        <div class="flex items-center justify-between border-b pb-4 mb-6">
                            <h2 class="text-xl font-bold text-gray-800">Shipping Details</h2>
                            @if($address)
                                <span class="text-xs bg-green-100 text-green-700 font-semibold px-2.5 py-1 rounded-full">Default Address Loaded</span>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Full Name *</label>
                                <input type="text" name="name" value="{{ old('name', $address->name ?? auth()->user()->name) }}" required class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary bg-white text-sm">
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Phone Number *</label>
                                <input type="text" name="phone" value="{{ old('phone', $address->phone ?? auth()->user()->mobile ?? '') }}" required class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary bg-white text-sm">
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Postal / Pincode *</label>
                                <input type="text" name="zip" value="{{ old('zip', $address->zip ?? '') }}" required class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary bg-white text-sm">
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">State / Province *</label>
                                <input type="text" name="state" value="{{ old('state', $address->state ?? '') }}" required class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary bg-white text-sm">
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Town / City *</label>
                                <input type="text" name="city" value="{{ old('city', $address->city ?? '') }}" required class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary bg-white text-sm">
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700">Landmark</label>
                                <input type="text" name="landmark" value="{{ old('landmark', $address->landmark ?? '') }}" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary bg-white text-sm" placeholder="e.g. Near City Center">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block mb-2 text-sm font-medium text-gray-700">House No, Building Name *</label>
                                <input type="text" name="address" value="{{ old('address', $address->address ?? '') }}" required class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary bg-white text-sm" placeholder="e.g. Apartment, suite, unit">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block mb-2 text-sm font-medium text-gray-700">Road Name, Area, Colony *</label>
                                <input type="text" name="locality" value="{{ old('locality', $address->locality ?? '') }}" required class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary bg-white text-sm" placeholder="e.g. Main Street, Sector 5">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Your Order & Payment -->
                <div class="w-full lg:w-5/12">
                    <div class="bg-gray-50 p-6 md:p-8 rounded-xl border border-gray-200 shadow-sm space-y-6">
                        <h2 class="text-xl font-bold text-gray-800 border-b pb-4">Your Order</h2>

                        <!-- Items Table -->
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <table class="w-full text-left text-sm">
                                <thead>
                                    <tr class="bg-gray-100 border-b text-gray-600 font-semibold">
                                        <th class="p-3.5">Product</th>
                                        <th class="p-3.5 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($items as $item)
                                        <tr>
                                            <td class="p-3.5 flex items-center gap-3">
                                                @if($item->model && $item->model->image)
                                                    <img src="{{ asset('uploads/products/thumbnails/'.$item->model->image) }}" onerror="this.onerror=null; this.src='{{ asset('uploads/products/'.$item->model->image) }}';" class="w-12 h-12 object-cover rounded border" alt="{{ $item->name }}">
                                                @else
                                                    <div class="w-12 h-12 bg-gray-100 rounded border flex items-center justify-center text-gray-400">
                                                        <i class="fa-solid fa-image"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <span class="font-medium text-gray-800 block line-clamp-1">{{ $item->name }}</span>
                                                    <span class="text-xs text-gray-500">Qty: {{ $item->qty }} &times; ${{ number_format($item->price, 2) }}</span>
                                                </div>
                                            </td>
                                            <td class="p-3.5 text-right font-semibold text-gray-800">
                                                ${{ number_format($item->subtotal(), 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50/70 divide-y divide-gray-100 text-sm">
                                    <tr>
                                        <td class="p-3.5 font-medium text-gray-600">Subtotal</td>
                                        <td class="p-3.5 text-right font-semibold text-gray-800">${{ number_format(Cart::instance('cart')->subtotal(), 2) }}</td>
                                    </tr>

                                    @if(Session::has('discounts'))
                                        <tr class="text-green-600">
                                            <td class="p-3.5 font-medium">Discount ({{ Session::get('coupon')['code'] }})</td>
                                            <td class="p-3.5 text-right font-semibold">-${{ number_format((float)Session::get('discounts')['discount'], 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="p-3.5 font-medium text-gray-600">Subtotal after Discount</td>
                                            <td class="p-3.5 text-right font-semibold text-gray-800">${{ number_format((float)Session::get('discounts')['subtotal'], 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="p-3.5 font-medium text-gray-600">Tax (10%)</td>
                                            <td class="p-3.5 text-right font-semibold text-gray-800">${{ number_format((float)Session::get('discounts')['tax'], 2) }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td class="p-3.5 font-medium text-gray-600">Tax (10%)</td>
                                            <td class="p-3.5 text-right font-semibold text-gray-800">${{ number_format(Cart::instance('cart')->tax(), 2) }}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td class="p-3.5 font-medium text-gray-600">Shipping</td>
                                        <td class="p-3.5 text-right font-semibold text-green-600">FREE</td>
                                    </tr>
                                    <tr class="text-base font-bold text-gray-900 bg-gray-100">
                                        <td class="p-4">Total</td>
                                        <td class="p-4 text-right text-primary text-xl">
                                            @if(Session::has('discounts'))
                                                ${{ number_format((float)Session::get('discounts')['total'], 2) }}
                                            @else
                                                ${{ number_format(Cart::instance('cart')->total(), 2) }}
                                            @endif
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Payment Methods -->
                        <div class="space-y-3">
                            <h3 class="font-bold text-gray-800 text-sm uppercase tracking-wider mb-2">Payment Method</h3>

                            <!-- COD -->
                            <label class="block bg-white border border-gray-200 rounded-lg p-4 cursor-pointer hover:border-primary transition">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="mode" value="cod" checked class="text-primary focus:ring-primary">
                                    <div>
                                        <span class="font-bold text-gray-800 text-sm block">Cash on Delivery (COD)</span>
                                        <span class="text-xs text-gray-500">Pay in cash when your order is delivered to your doorstep.</span>
                                    </div>
                                </div>
                            </label>

                            <!-- Card -->
                            <label class="block bg-white border border-gray-200 rounded-lg p-4 cursor-pointer hover:border-primary transition">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="mode" value="card" class="text-primary focus:ring-primary">
                                    <div>
                                        <span class="font-bold text-gray-800 text-sm block">Credit / Debit Card</span>
                                        <span class="text-xs text-gray-500">Pay securely with Visa, MasterCard, or American Express.</span>
                                    </div>
                                </div>
                            </label>

                            <!-- PayPal -->
                            <label class="block bg-white border border-gray-200 rounded-lg p-4 cursor-pointer hover:border-primary transition">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="mode" value="paypal" class="text-primary focus:ring-primary">
                                    <div>
                                        <span class="font-bold text-gray-800 text-sm block">PayPal</span>
                                        <span class="text-xs text-gray-500">Fast and secure checkout via your PayPal account.</span>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <button type="submit" class="w-full bg-primary hover:bg-blue-600 text-white font-bold py-3.5 rounded-lg transition shadow-md flex items-center justify-center gap-2">
                            <i class="fa-solid fa-lock text-sm"></i>
                            <span>Place Order Now</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

