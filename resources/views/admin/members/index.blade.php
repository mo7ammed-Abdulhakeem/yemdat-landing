<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 flex justify-between items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-yemdat-brown hover:text-yemdat-gold flex items-center gap-2 font-medium">
                    <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Dashboard
                </a>
                <h2 class="text-2xl font-bold text-yemdat-brown">All Members</h2>
                <div class="flex gap-4 items-center">
                    <form action="{{ route('admin.members.index') }}" method="GET" class="flex items-center">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search members..." class="rounded-l-md border-gray-300 focus:border-yemdat-gold focus:ring-yemdat-gold text-sm">
                        <button type="submit" class="bg-yemdat-brown text-white px-4 py-2 rounded-r-md hover:bg-yemdat-gold transition text-sm font-medium">Search</button>
                    </form>
                    <a href="{{ route('admin.members.export') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Export to CSV
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name / Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($members as $member)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('admin.members.show', $member) }}" class="hover:text-yemdat-gold hover:underline">
                                            {{ $member->full_name }}
                                        </a>
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 capitalize">
                                        {{ $member->membershipTier ? $member->membershipTier->name : ucfirst($member->membership_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $member->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('admin.members.show', $member) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        <a href="{{ route('admin.members.edit', $member) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                        <form action="{{ route('admin.members.destroy', $member) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this member?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No members found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="p-4 border-t border-gray-200">
                    {{ $members->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
