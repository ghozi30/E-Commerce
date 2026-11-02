@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Overview</h1>
        <p class="text-sm text-gray-500">Welcome to your administration control center</p>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Sales</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-2">${{ number_format($totalSales ?? 0, 2) }}</h3>
                <p class="text-xs text-green-600 mt-1 flex items-center gap-1 font-medium">
                    <i class="fa-solid fa-circle-check"></i> Non-canceled
                </p>
            </div>
            <div class="p-3 bg-blue-50 rounded-lg text-primary">
                <i class="fa-solid fa-dollar-sign text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Orders</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-2">{{ $totalOrders ?? 0 }}</h3>
                <p class="text-xs text-purple-600 mt-1 flex items-center gap-1 font-medium">
                    <i class="fa-solid fa-cart-shopping"></i> Customer orders
                </p>
            </div>
            <div class="p-3 bg-purple-50 rounded-lg text-purple-600">
                <i class="fa-solid fa-bag-shopping text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Customers</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-2">{{ $totalCustomers ?? 0 }}</h3>
                <p class="text-xs text-orange-600 mt-1 flex items-center gap-1 font-medium">
                    <i class="fa-solid fa-users"></i> Registered
                </p>
            </div>
            <div class="p-3 bg-orange-50 rounded-lg text-orange-600">
                <i class="fa-solid fa-users text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Products</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-2">{{ $totalProducts ?? 0 }}</h3>
                <p class="text-xs text-green-600 mt-1 flex items-center gap-1 font-medium">
                    <i class="fa-solid fa-box"></i> In catalog
                </p>
            </div>
            <div class="p-3 bg-green-50 rounded-lg text-green-600">
                <i class="fa-solid fa-box text-xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-bold text-gray-800">Recent Orders</h3>
        <a href="{{ route('admin.orders') }}" class="text-sm text-primary hover:underline font-semibold">View All Orders &rarr;</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                <tr>
                    <th class="px-6 py-3">Order ID</th>
                    <th class="px-6 py-3">Customer</th>
                    <th class="px-6 py-3">Phone</th>
                    <th class="px-6 py-3">Subtotal</th>
                    <th class="px-6 py-3">Tax</th>
                    <th class="px-6 py-3">Total</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Order Date</th>
                    <th class="px-6 py-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recentOrders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3.5 font-bold text-gray-800">
                            #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-6 py-3.5 font-medium text-gray-900">{{ $order->name }}</td>
                        <td class="px-6 py-3.5 text-gray-600">{{ $order->phone }}</td>
                        <td class="px-6 py-3.5 text-gray-700">${{ number_format($order->subtotal, 2) }}</td>
                        <td class="px-6 py-3.5 text-gray-700">${{ number_format($order->tax, 2) }}</td>
                        <td class="px-6 py-3.5 font-bold text-primary">${{ number_format($order->total, 2) }}</td>
                        <td class="px-6 py-3.5">
                            @if ($order->status == 'delivered')
                                <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-semibold uppercase">Delivered</span>
                            @elseif ($order->status == 'canceled')
                                <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-xs font-semibold uppercase">Canceled</span>
                            @else
                                <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs font-semibold uppercase">Ordered</span>
                            @endif
                        </td>
                        <td class="px-6 py-3.5 text-gray-600">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-3.5 text-center">
                            <a href="{{ route('admin.order.details', ['order_id' => $order->id]) }}" class="text-primary hover:underline font-semibold text-xs">
                                Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-8 text-center text-gray-400">
                            <i class="fa-solid fa-inbox text-3xl mb-2 block"></i>
                            No orders recorded yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
