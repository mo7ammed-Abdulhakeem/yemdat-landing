<x-admin-layout>
    <x-slot name="header">
        All Messages
    </x-slot>

    <div>
        <div class="mb-6 flex justify-end items-center">
            <div class="flex gap-4 items-center">
                <form action="{{ route('admin.messages.index') }}" method="GET" class="flex items-center">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search messages..." class="rounded-l-md border-gray-300 focus:border-yemdat-gold focus:ring-yemdat-gold text-sm">
                    <button type="submit" class="bg-yemdat-brown text-white px-4 py-2 rounded-r-md hover:bg-yemdat-gold transition text-sm font-medium">Search</button>
                </form>
                <a href="{{ route('admin.messages.export') }}" class="inline-flex items-center px-4 py-2 border border-green-600 rounded-md shadow-sm text-sm font-medium text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export CSV
                </a>
            </div>
        </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sender</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($messages as $message)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($message->member_id)
                                        <a href="{{ route('admin.members.show', $message->member_id) }}" class="text-sm font-bold text-yemdat-gold hover:underline flex items-center gap-1">
                                            {{ $message->name }}
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        </a>
                                    @else
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('admin.messages.show', $message) }}" class="hover:text-yemdat-gold hover:underline">
                                                {{ $message->name }}
                                            </a>
                                        </div>
                                    @endif
                                    <div class="text-sm text-gray-500">{{ $message->email }}</div>
                                    @if($message->phone_number)
                                        <div class="text-xs text-gray-400 mt-1" dir="ltr">{{ $message->phone_number }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <a href="{{ route('admin.messages.show', $message) }}" class="hover:text-yemdat-gold hover:underline">
                                        {{ Str::limit($message->subject, 30) }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $message->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('admin.messages.show', $message) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No messages found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="p-4 border-t border-gray-200">
                    {{ $messages->links() }}
                </div>
            </div>

    </div>
</x-admin-layout>
