@extends('layouts.admin')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Products</h1>
        <p class="text-sm text-gray-500">Manage your product catalog and inventory</p>
    </div>
    <a href="{{ route('admin.product.add') }}" class="bg-primary hover:bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2 shadow-sm">
        <i class="fa-solid fa-plus"></i> Add New Product
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
                    <th class="px-6 py-4">Product Name</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4">Brand</th>
                    <th class="px-6 py-4">Price</th>
                    <th class="px-6 py-4">SKU</th>
                    <th class="px-6 py-4">Stock</th>
                    <th class="px-6 py-4">Featured</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-500">#{{ $product->id }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-lg border bg-gray-50 flex items-center justify-center overflow-hidden flex-shrink-0">
                                    @if($product->image)
                                        <img src="{{ asset('uploads/products/' . $product->image) }}" class="w-full h-full object-cover" alt="{{ $product->name }}" onerror="this.onerror=null; this.src='https://placehold.co/50x50?text=P';">
                                    @else
                                        <i class="fa-solid fa-box text-gray-400"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $product->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $product->category->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $product->brand->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($product->sale_price)
                                <span class="font-semibold text-red-600">${{ number_format($product->sale_price, 2) }}</span>
                                <span class="text-xs text-gray-400 line-through ml-1">${{ number_format($product->regular_price, 2) }}</span>
                            @else
                                <span class="font-semibold text-gray-800">${{ number_format($product->regular_price, 2) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $product->SKU }}</td>
                        <td class="px-6 py-4 text-sm">
                            @if($product->stock_status == 'instock')
                                <span class="bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-xs font-semibold">
                                    In Stock ({{ $product->quantity }})
                                </span>
                            @else
                                <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-xs font-semibold">
                                    Out of Stock
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($product->featured)
                                <span class="bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full text-xs font-semibold">
                                    <i class="fa-solid fa-star text-[10px]"></i> Yes
                                </span>
                            @else
                                <span class="text-gray-400 text-xs">No</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.product.edit', ['id' => $product->id]) }}" class="w-8 h-8 rounded-full hover:bg-gray-100 text-blue-500 transition flex items-center justify-center" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                
                                <form action="{{ route('admin.product.delete', ['id' => $product->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete product {{ $product->name }}?');" class="inline">
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
                        <td colspan="9" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <i class="fa-solid fa-boxes-stacked text-4xl mb-3 text-gray-300"></i>
                                <h3 class="text-lg font-medium text-gray-900">No products available</h3>
                                <p class="text-sm mt-1">You haven't added any products to your store yet.</p>
                                <a href="{{ route('admin.product.add') }}" class="mt-4 text-primary hover:underline text-sm font-medium">
                                    + Add your first product
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection

