@extends('layouts.admin')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Brands</h1>
        <p class="text-sm text-gray-500">Manage product brands and partners</p>
    </div>
    <a href="{{ route('admin.brand.add') }}" class="bg-primary hover:bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2 shadow-sm">
        <i class="fa-solid fa-plus"></i> Add New Brand
    </a>
</div>

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

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left whitespace-nowrap">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                <tr>
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Logo</th>
                    <th class="px-6 py-4">Brand Name</th>
                    <th class="px-6 py-4">Slug</th>
                    <th class="px-6 py-4">Products</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($brands as $brand)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-500">#{{ $brand->id }}</td>
                        <td class="px-6 py-4">
                            <div class="w-12 h-12 bg-gray-50 rounded-lg flex items-center justify-center border overflow-hidden">
                                @if($brand->image)
                                    <img src="{{ asset('uploads/brands/' . $brand->image) }}" class="max-w-[40px] max-h-[40px] object-contain" alt="{{ $brand->name }}" onerror="this.onerror=null; this.src='https://placehold.co/40x40?text=B';">
                                @else
                                    <span class="text-sm font-bold text-gray-400">{{ strtoupper(substr($brand->name, 0, 1)) }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-gray-800">{{ $brand->name }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $brand->slug }}</td>
                        <td class="px-6 py-4">
                            <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-semibold">
                                {{ method_exists($brand, 'products') ? $brand->products()->count() : 0 }} Items
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.brand.edit', ['id' => $brand->id]) }}" class="w-8 h-8 rounded-full hover:bg-gray-100 text-blue-500 transition flex items-center justify-center" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                
                                <form action="{{ route('admin.brand.delete', ['id' => $brand->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete brand {{ $brand->name }}?');" class="inline">
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
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <i class="fa-solid fa-boxes-stacked text-4xl mb-3 text-gray-300"></i>
                                <h3 class="text-lg font-medium text-gray-900">Brands not available</h3>
                                <p class="text-sm mt-1">You haven't added any brands to your store yet.</p>
                                <a href="{{ route('admin.brand.add') }}" class="mt-4 text-primary hover:underline text-sm font-medium">
                                    + Add your first brand
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($brands->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $brands->links() }}
        </div>
    @endif
</div>
@endsection
