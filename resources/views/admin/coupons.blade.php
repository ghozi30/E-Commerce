@extends('layouts.admin')

@section('content')
<main class="flex-1 overflow-y-auto p-6 bg-gray-100">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Coupons</h1>
            <p class="text-sm text-gray-500">Manage discount codes and promotional offers</p>
        </div>
        <a href="{{ route('admin.coupon.add') }}" class="bg-primary hover:bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-plus"></i> Add New Coupon
        </a>
    </div>

    @if (Session::has('status'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm flex items-center justify-between">
            <span>{{ Session::get('status') }}</span>
            <button type="button" onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">&times;</button>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Code</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Value</th>
                        <th class="px-6 py-4">Min. Cart Value</th>
                        <th class="px-6 py-4">Expiry Date</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($coupons as $coupon)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-500">#{{ $coupon->id }}</td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-800 tracking-wider">{{ $coupon->code }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if ($coupon->type == 'percent')
                                    <span class="bg-purple-50 text-purple-700 px-2 py-1 rounded text-xs font-semibold uppercase">Percent</span>
                                @else
                                    <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-semibold uppercase">Fixed</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                {{ $coupon->type == 'percent' ? $coupon->value.'%' : '$'.number_format($coupon->value, 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">${{ number_format($coupon->cart_value, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600"><i class="fa-regular fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($coupon->expiry_date)->format('Y-m-d') }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.coupon.edit', ['id' => $coupon->id]) }}" class="w-8 h-8 rounded-full hover:bg-gray-100 text-blue-500 transition flex items-center justify-center" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.coupon.delete', ['id' => $coupon->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this coupon?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-full hover:bg-gray-100 text-red-500 transition flex items-center justify-center" title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <i class="fa-solid fa-ticket text-4xl mb-3 text-gray-300"></i>
                                    <h3 class="text-lg font-medium text-gray-900">No coupons available</h3>
                                    <p class="text-sm mt-1">You haven't created any discount codes yet.</p>
                                    <a href="{{ route('admin.coupon.add') }}" class="mt-4 text-primary hover:underline text-sm font-medium">
                                        Create your first coupon
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($coupons->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>
</main>
@endsection

