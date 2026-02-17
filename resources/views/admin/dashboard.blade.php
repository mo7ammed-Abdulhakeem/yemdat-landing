<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <h2 class="font-semibold text-2xl text-yemdat-brown leading-tight">
                    Admin Dashboard
                </h2>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <div class="flex gap-4 items-center">
                        <a href="{{ route('admin.membership-tiers.index') }}" class="text-sm font-medium text-gray-600 hover:text-yemdat-gold underline">Membership Plans</a>
                        <a href="{{ route('admin.settings') }}" class="text-sm font-medium text-gray-600 hover:text-yemdat-gold underline">Site Settings</a>
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium bg-white px-4 py-2 rounded-lg border border-red-200 hover:bg-red-50 transition">
                            Logout
                        </button>
                    </div>
                </form>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                <!-- Total Members -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border-l-4 border-yemdat-brown p-6 flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Total Members</div>
                        <div class="text-3xl font-bold text-yemdat-brown">{{ $totalMembers }}</div>
                    </div>
                    <div class="p-3 bg-yemdat-beige rounded-full text-yemdat-brown">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>

                <!-- Total Messages -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border-l-4 border-yemdat-gold p-6 flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Total Messages</div>
                        <div class="text-3xl font-bold text-yemdat-brown">{{ $totalMessages }}</div>
                    </div>
                    <div class="p-3 bg-yellow-50 rounded-full text-yemdat-gold">
                         <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Recent Members -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h3 class="text-lg font-bold text-yemdat-brown">Recent Members</h3>
                        <div class="flex gap-3">
                            <a href="{{ route('admin.members.export') }}" class="text-sm text-green-600 hover:text-green-800">Export CSV</a>
                            <a href="{{ route('admin.members.index') }}" class="text-sm text-yemdat-gold hover:text-yemdat-brown">View All</a>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name / Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
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
                                            {{ $member->membership_type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $member->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No members found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Messages -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h3 class="text-lg font-bold text-yemdat-brown">Recent Messages</h3>
                        <div class="flex gap-3">
                            <a href="{{ route('admin.messages.export') }}" class="text-sm text-green-600 hover:text-green-800">Export CSV</a>
                            <a href="{{ route('admin.messages.index') }}" class="text-sm text-yemdat-gold hover:text-yemdat-brown">View All</a>
                        </div>
                    </div>
                     <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sender</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                             <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($messages as $message)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $message->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $message->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <a href="{{ route('admin.messages.show', $message) }}" class="hover:text-yemdat-gold hover:underline">
                                            {{ Str::limit($message->subject, 20) }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $message->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No messages found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
