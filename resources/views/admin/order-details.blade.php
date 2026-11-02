@extends('layouts.admin')

@section('content')
<main class="flex-1 overflow-y-auto p-6 bg-gray-100">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Order #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }} Details
            </h1>
            <p class="text-sm text-gray-500">Ordered on {{ $order->created_at->format('F d, Y \a\t H:i') }}</p>
        </div>
        <a href="{{ route('admin.orders') }}" class="border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-arrow-left"></i> Back to Orders
        </a>
    </div>

    @if (Session::has('status'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm flex items-center justify-between">
            <span>{{ Session::get('status') }}</span>
            <button type="button" onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">&times;</button>
        </div>
    @endif

    <!-- Ordered Items Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-800 text-base">Ordered Items</h2>
            <span class="text-xs font-semibold px-2.5 py-1 bg-gray-100 rounded-full text-gray-600">
                Total Items: {{ $order->orderItems->count() }}
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                    <tr>
                        <th class="px-6 py-4">Product</th>
                        <th class="px-6 py-4">SKU</th>
                        <th class="px-6 py-4">Price</th>
                        <th class="px-6 py-4 text-center">Quantity</th>
                        <th class="px-6 py-4 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($orderItems as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if ($item->product && $item->product->image)
                                        <img src="{{ asset('uploads/products/thumbnails/'.$item->product->image) }}" onerror="this.onerror=null; this.src='{{ asset('uploads/products/'.$item->product->image) }}';" class="w-12 h-12 object-cover rounded-lg border border-gray-100" alt="{{ $item->product->name }}">
                                    @else
                                        <div class="w-12 h-12 bg-gray-100 rounded-lg border flex items-center justify-center text-gray-400">
                                            <i class="fa-solid fa-image"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $item->product->name ?? 'Product' }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->product->category->name ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $item->product->SKU ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-gray-700">${{ number_format($item->price, 2) }}</td>
                            <td class="px-6 py-4 text-center font-semibold text-gray-800">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900">${{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($orderItems->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $orderItems->links() }}
            </div>
        @endif
    </div>

    <!-- 3-Column Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Shipping Address -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 text-base border-b pb-3 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-location-dot text-primary"></i> Shipping Address
            </h3>
            <div class="text-sm text-gray-600 space-y-1.5 leading-relaxed">
                <p class="font-bold text-gray-900 text-base">{{ $order->name }}</p>
                <p>{{ $order->address }}</p>
                <p>{{ $order->locality }}</p>
                <p>{{ $order->city }}, {{ $order->state }} - {{ $order->zip }}</p>
                <p>{{ $order->country }}</p>
                <p class="pt-2 text-gray-800 font-medium">
                    <i class="fa-solid fa-phone mr-1 text-gray-400"></i> {{ $order->phone }}
                </p>
            </div>
        </div>

        <!-- Order Summary & Transaction -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 text-base border-b pb-3 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-receipt text-primary"></i> Order Summary
            </h3>
            <ul class="text-sm space-y-2.5">
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
                <li class="flex justify-between text-gray-900 border-t pt-2 text-base font-bold">
                    <span>Total:</span>
                    <span class="text-primary text-lg">${{ number_format($order->total, 2) }}</span>
                </li>
                <li class="flex justify-between text-gray-600 pt-2 border-t">
                    <span>Payment Mode:</span>
                    <span class="uppercase font-semibold text-gray-800">{{ $transaction->mode ?? 'COD' }}</span>
                </li>
                <li class="flex justify-between text-gray-600">
                    <span>Payment Status:</span>
                    <span class="uppercase font-semibold {{ ($transaction->status ?? '') === 'approved' ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ $transaction->status ?? 'pending' }}
                    </span>
                </li>
            </ul>
        </div>

        <!-- Update Order Status Form -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 text-base border-b pb-3 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-primary"></i> Update Order Status
            </h3>

            <div class="mb-4 text-sm">
                <span class="text-gray-500 block mb-1">Current Status:</span>
                @if ($order->status == 'delivered')
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold uppercase inline-block">Delivered</span>
                    @if ($order->delivered_date)
                        <p class="text-xs text-gray-500 mt-2">Delivered on: {{ \Carbon\Carbon::parse($order->delivered_date)->format('F d, Y') }}</p>
                    @endif
                @elseif ($order->status == 'canceled')
                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold uppercase inline-block">Canceled</span>
                    @if ($order->canceled_date)
                        <p class="text-xs text-gray-500 mt-2">Canceled on: {{ \Carbon\Carbon::parse($order->canceled_date)->format('F d, Y') }}</p>
                    @endif
                @else
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold uppercase inline-block">Ordered</span>
                @endif
            </div>

            <form action="{{ route('admin.order.status.update') }}" method="POST" class="space-y-4 pt-2">
                @csrf
                @method('PUT')
                <input type="hidden" name="order_id" value="{{ $order->id }}">

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase mb-2">Change Status</label>
                    <select name="order_status" class="w-full border px-3 py-2.5 rounded-lg text-sm bg-white focus:outline-none focus:border-primary">
                        <option value="ordered" {{ $order->status == 'ordered' ? 'selected' : '' }}>Ordered</option>
                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-primary hover:bg-blue-600 text-white font-medium py-2.5 rounded-lg text-sm transition shadow-sm">
                    Update Status
                </button>
            </form>
        </div>
    </div>
</main>
@endsection

