<x-admin-layout>
    <x-slot name="header">
        Dashboard Overview
    </x-slot>

    <div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <!-- Total Members -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border-l-4 border-yemdat-brown p-6 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Total Members</div>
                            <div class="flex items-baseline gap-2">
                                <div class="text-3xl font-bold text-yemdat-brown">{{ $totalMembers }}</div>
                                @if($membersJoinedToday > 0)
                                    <span class="px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-xs font-bold" title="Joined Today">
                                        +{{ $membersJoinedToday }} Today
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="p-3 bg-yemdat-beige rounded-full text-yemdat-brown">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                    
                    <!-- Breakdowns -->
                    <div class="space-y-3 mt-1">
                        <!-- Membership Tier Breakdown -->
                        <div class="border-t border-gray-100 pt-3">
                            <div class="text-xs font-semibold text-gray-400 uppercase mb-2">By Plan</div>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                @foreach($membershipTiers as $tier)
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 truncate mr-1" title="{{ $tier->name }}">{{ Str::limit($tier->name, 15) }}</span>
                                        <span class="font-bold text-yemdat-brown bg-gray-100 px-1.5 py-0.5 rounded">{{ $memberCounts[$tier->slug] ?? 0 }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                         <!-- Gender Breakdown -->
                         <div class="border-t border-gray-100 pt-3">
                            <div class="text-xs font-semibold text-gray-400 uppercase mb-2">By Gender</div>
                            <div class="grid grid-cols-2 gap-4 text-xs">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Male</span>
                                    <span class="font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">{{ $genderCounts['male'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Female</span>
                                    <span class="font-bold text-pink-600 bg-pink-50 px-1.5 py-0.5 rounded">{{ $genderCounts['female'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
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

                <!-- Total Events -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border-l-4 border-blue-500 p-6 flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Total Events</div>
                        <div class="text-3xl font-bold text-yemdat-brown">{{ $totalEvents }}</div>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-full text-blue-500">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border-l-4 border-green-500 p-6 flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Upcoming Events</div>
                        <div class="text-3xl font-bold text-yemdat-brown">{{ $upcomingEventsCount }}</div>
                    </div>
                    <div class="p-3 bg-green-50 rounded-full text-green-500">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
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
                                            <form action="{{ route('admin.members.destroy', $member) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this member?');" class="inline">
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
</x-admin-layout>
