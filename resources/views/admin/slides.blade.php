@extends('layouts.admin')

@section('content')
<main class="flex-1 overflow-y-auto p-6 bg-gray-100">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Home Slider</h1>
            <p class="text-sm text-gray-500">Manage promotional slides and banners on the homepage</p>
        </div>
        <a href="{{ route('admin.slide.add') }}" class="bg-primary hover:bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-plus"></i> Add New Slide
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
            <table class="w-full text-left whitespace-nowrap text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Image</th>
                        <th class="px-6 py-4">Tagline</th>
                        <th class="px-6 py-4">Title</th>
                        <th class="px-6 py-4">Subtitle</th>
                        <th class="px-6 py-4">Link</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($slides as $slide)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-500 font-semibold">#{{ $slide->id }}</td>
                            <td class="px-6 py-4">
                                <img src="{{ asset('uploads/slides/'.$slide->image) }}" onerror="this.onerror=null; this.src='{{ asset('assets/images/slider/'.$slide->image) }}';" class="w-20 h-12 object-contain bg-gray-50 rounded border border-gray-100" alt="{{ $slide->title }}">
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded text-xs font-semibold">{{ $slide->tagline }}</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-800">{{ $slide->title }}</td>
                            <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="{{ $slide->subtitle }}">{{ $slide->subtitle }}</td>
                            <td class="px-6 py-4 text-gray-500 text-xs">
                                <a href="{{ $slide->link }}" target="_blank" class="text-primary hover:underline">{{ $slide->link }}</a>
                            </td>
                            <td class="px-6 py-4">
                                @if ($slide->status)
                                    <span class="bg-green-100 text-green-700 px-2.5 py-0.5 rounded-full text-xs font-semibold">Active</span>
                                @else
                                    <span class="bg-gray-100 text-gray-500 px-2.5 py-0.5 rounded-full text-xs font-semibold">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.slide.edit', ['id' => $slide->id]) }}" class="w-8 h-8 rounded-full hover:bg-gray-100 text-blue-500 transition flex items-center justify-center" title="Edit Slide">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.slide.delete', ['id' => $slide->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this slide?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-full hover:bg-red-50 text-red-500 transition inline-flex items-center justify-center" title="Delete Slide">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <i class="fa-solid fa-images text-4xl mb-3 text-gray-300"></i>
                                <h3 class="text-lg font-medium text-gray-900">No slides created</h3>
                                <p class="text-sm mt-1">Add banners to feature products on your homepage slider.</p>
                                <a href="{{ route('admin.slide.add') }}" class="mt-4 inline-block text-primary hover:underline text-sm font-medium">
                                    Create your first slide
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($slides->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $slides->links() }}
            </div>
        @endif
    </div>
</main>
@endsection
