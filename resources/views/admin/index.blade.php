@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Overview</h1>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Sales</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-2">$0.00</h3>
                <p class="text-sm text-green-500 mt-1 flex items-center gap-1">
                    <i class="fa-solid fa-arrow-trend-up"></i> +0%
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
                <h3 class="text-2xl font-bold text-gray-800 mt-2">0</h3>
                <p class="text-sm text-green-500 mt-1 flex items-center gap-1">
                    <i class="fa-solid fa-arrow-trend-up"></i> +0%
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
                <h3 class="text-2xl font-bold text-gray-800 mt-2">1</h3>
                <p class="text-sm text-green-500 mt-1 flex items-center gap-1">
                    <i class="fa-solid fa-users"></i> Active
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
                <h3 class="text-2xl font-bold text-gray-800 mt-2">0</h3>
                <p class="text-sm text-green-500 mt-1 flex items-center gap-1">
                    <i class="fa-solid fa-box"></i> In Catalog
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
        <a href="#" class="text-sm text-primary hover:underline">View All</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
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
                    <th class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                <tr>
                    <td colspan="9" class="px-6 py-8 text-center text-gray-400">
                        <i class="fa-solid fa-inbox text-3xl mb-2 block"></i>
                        No orders recorded yet.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
