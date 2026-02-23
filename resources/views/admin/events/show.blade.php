<x-admin-layout>
    <x-slot name="header">
        Event Registrations: {{ $event->title }}
    </x-slot>

    <div>
        <div class="mb-6 flex justify-between items-center">
            <div class="flex items-center gap-4">
                 <a href="{{ route('admin.events.index') }}" class="text-yemdat-brown hover:text-yemdat-gold flex items-center gap-1 font-medium text-sm">
                    <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Events
                </a>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('events.show', $event->slug) }}" target="_blank" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded shadow transition text-sm">
                    View Public Page
                </a>
                <a href="{{ route('admin.events.export', $event) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white flex items-center gap-2 py-2 px-4 rounded shadow transition text-sm font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export CSV
                </a>
                <div class="bg-yemdat-gold text-yemdat-brown px-4 py-2 rounded shadow font-bold text-sm">
                    Total Registrations: {{ $event->members()->count() }}
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Registered Members</h3>
                
                @if($event->members->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Specialty</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered At</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($event->members as $member)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('admin.members.show', $member) }}" class="font-bold text-yemdat-brown hover:underline">
                                                {{ $member->full_name }}
                                            </a>
                                            <div class="text-xs text-gray-500">{{ $member->membershipTier->name_en ?? 'Member' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $member->email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" dir="ltr">
                                            {{ $member->phone_code }} {{ $member->phone_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $member->specialty === 'Other' ? ($member->specialty_other ?? 'Other') : $member->specialty }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $member->pivot->created_at->format('M d, Y h:i A') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        No members have registered for this event yet.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
