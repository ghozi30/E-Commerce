@extends('layouts.admin')

@section('content')
<main class="flex-1 overflow-y-auto p-6 bg-gray-100">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Orders</h1>
            <p class="text-sm text-gray-500">Manage and track customer orders</p>
        </div>
    </div>

    @if (Session::has('status'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm flex items-center justify-between">
            <span>{{ Session::get('status') }}</span>
            <button type="button" onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">&times;</button>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                    <tr>
                        <th class="px-6 py-4">Order No</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Phone</th>
                        <th class="px-6 py-4">Subtotal</th>
                        <th class="px-6 py-4">Tax</th>
                        <th class="px-6 py-4">Discount</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Payment</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Order Date</th>
                        <th class="px-6 py-4">Total Items</th>
                        <th class="px-6 py-4">Delivered On</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-bold text-gray-800">
                                #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $order->name }}</div>
                                <div class="text-xs text-gray-500">{{ $order->user->email ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $order->phone }}</td>
                            <td class="px-6 py-4 text-gray-700">${{ number_format($order->subtotal, 2) }}</td>
                            <td class="px-6 py-4 text-gray-700">${{ number_format($order->tax, 2) }}</td>
                            <td class="px-6 py-4 text-green-600 font-medium">
                                {{ $order->discount > 0 ? '-$'.number_format($order->discount, 2) : '$0.00' }}
                            </td>
                            <td class="px-6 py-4 font-bold text-primary">${{ number_format($order->total, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="uppercase text-xs font-semibold px-2 py-1 rounded bg-gray-100 text-gray-700">
                                    {{ $order->transaction->mode ?? 'COD' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if ($order->status == 'delivered')
                                    <span class="bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-xs font-semibold uppercase">Delivered</span>
                                @elseif ($order->status == 'canceled')
                                    <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-xs font-semibold uppercase">Canceled</span>
                                @else
                                    <span class="bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full text-xs font-semibold uppercase">Ordered</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 text-center font-semibold text-gray-700">{{ $order->orderItems->count() }}</td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $order->delivered_date ? \Carbon\Carbon::parse($order->delivered_date)->format('Y-m-d') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.order.details', ['order_id' => $order->id]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-sky-50 text-sky-600 hover:bg-sky-100 rounded-lg font-medium text-xs transition" title="View Details">
                                    <i class="fa-solid fa-eye"></i> Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <i class="fa-solid fa-cart-shopping text-4xl mb-3 text-gray-300"></i>
                                    <h3 class="text-lg font-medium text-gray-900">No orders found</h3>
                                    <p class="text-sm mt-1">There are currently no customer orders placed.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</main>
@endsection

