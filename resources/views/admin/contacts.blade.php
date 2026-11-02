@extends('layouts.admin')

@section('content')
<main class="flex-1 overflow-y-auto p-6 bg-gray-100">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Messages & Contacts</h1>
            <p class="text-sm text-gray-500">View and manage customer inquiries and messages</p>
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
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Phone</th>
                        <th class="px-6 py-4">Subject</th>
                        <th class="px-6 py-4">Message</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($contacts as $contact)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-500 text-xs font-semibold">#{{ $contact->id }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $contact->name }}</td>
                            <td class="px-6 py-4 text-gray-600">
                                <a href="mailto:{{ $contact->email }}" class="text-primary hover:underline">{{ $contact->email }}</a>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $contact->phone ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-700 font-medium">{{ $contact->subject ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-600 max-w-xs truncate" title="{{ $contact->comment }}">
                                {{ $contact->comment }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-xs">{{ $contact->created_at->format('M d, Y H:i') }}</td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('admin.contact.delete', ['id' => $contact->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this message?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-full hover:bg-red-50 text-red-500 transition inline-flex items-center justify-center" title="Delete Message">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <i class="fa-solid fa-envelope-open text-3xl mb-3 text-gray-300"></i>
                                <h3 class="text-base font-semibold text-gray-800">No messages yet</h3>
                                <p class="text-xs text-gray-500 mt-1">Customer inquiries submitted through Contact Us will appear here.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($contacts->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $contacts->links() }}
            </div>
        @endif
    </div>
</main>
@endsection

