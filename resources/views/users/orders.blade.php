@extends('layouts.app')

@section('content')
<!-- Page Banner Start -->
<div class="relative bg-sky-700 text-white h-56 flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('assets/images/page-banner.jpg') }}');">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="relative z-10 text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-2">My Orders</h1>
        <ul class="flex justify-center items-center space-x-2 text-sm text-gray-200">
            <li><a href="{{ route('home.index') }}" class="hover:text-white transition">Home</a></li>
            <li>/</li>
            <li><a href="{{ route('user.index') }}" class="hover:text-white transition">Account</a></li>
            <li>/</li>
            <li class="text-sky-300 font-medium">Orders</li>
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
            <div class="w-full lg:w-3/4">
                <div class="bg-white border rounded-xl p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b flex items-center justify-between">
                        <span>Orders History</span>
                        <span class="text-xs font-semibold px-2.5 py-1 bg-gray-100 rounded-full text-gray-600">
                            {{ $orders->total() }} Total
                        </span>
                    </h2>

                    @if (Session::has('status'))
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm flex items-center justify-between">
                            <span>{{ Session::get('status') }}</span>
                            <button type="button" onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">&times;</button>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-left whitespace-nowrap text-sm">
                            <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                                <tr>
                                    <th class="px-4 py-3">Order No</th>
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Total</th>
                                    <th class="px-4 py-3">Payment</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($orders as $order)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3.5 font-bold text-gray-800">
                                            #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                        </td>
                                        <td class="px-4 py-3.5 text-gray-600">
                                            {{ $order->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-4 py-3.5 font-bold text-primary">
                                            ${{ number_format($order->total, 2) }}
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <span class="uppercase text-xs font-semibold px-2 py-0.5 rounded bg-gray-100 text-gray-700">
                                                {{ $order->transaction->mode ?? 'COD' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5">
                                            @if ($order->status == 'delivered')
                                                <span class="bg-green-100 text-green-700 px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase">Delivered</span>
                                            @elseif ($order->status == 'canceled')
                                                <span class="bg-red-100 text-red-700 px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase">Canceled</span>
                                            @else
                                                <span class="bg-blue-100 text-blue-700 px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase">Ordered</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5 text-center">
                                            <a href="{{ route('user.order.details', ['order_id' => $order->id]) }}" class="inline-flex items-center gap-1 px-3 py-1 bg-primary text-white hover:bg-blue-600 rounded text-xs font-medium transition shadow-sm">
                                                <i class="fa-solid fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                                            <i class="fa-solid fa-cart-shopping text-3xl mb-3 text-gray-300"></i>
                                            <p class="font-medium text-gray-800">No orders yet</p>
                                            <p class="text-xs text-gray-500 mt-1">Browse our store and make your first purchase!</p>
                                            <a href="{{ route('shop.index') }}" class="mt-3 inline-block text-primary hover:underline font-semibold text-xs">
                                                Start Shopping &rarr;
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($orders->hasPages())
                        <div class="pt-4 mt-4 border-t border-gray-100">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

