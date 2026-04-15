<x-admin-layout>
    <x-slot name="header">
        Events Management
    </x-slot>

    <div>
        <div class="mb-6 flex justify-between items-center">
            <div class="flex items-center gap-4">
                {{-- Breadcrumbs or Back Link if needed, but header has title now --}}
                 <!-- <a href="{{ route('admin.dashboard') }}" class="text-yemdat-brown hover:text-yemdat-gold flex items-center gap-1 font-medium text-sm">
                    <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Dashboard
                </a> -->
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.events.export_all') }}" class="inline-flex items-center px-4 py-2 border border-green-600 rounded-md shadow-sm text-sm font-medium text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export All CSV
                </a>
                <a href="{{ route('admin.events.create') }}" class="bg-yemdat-brown hover:bg-yemdat-gold text-white font-bold py-2 px-4 rounded-md shadow transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add New Event
                </a>
            </div>
        </div>

        @php
            $hasOrphans = \Illuminate\Support\Facades\DB::table('event_member')
                ->whereNotIn('event_id', function ($query) {
                    $query->select('id')->from('events');
                })->exists();
        @endphp

        @if($hasOrphans)
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-md shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-red-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <h3 class="text-sm font-bold text-red-800">Registration Disconnect Detected</h3>
                            <p class="text-sm text-red-700 mt-1">We detected event registrations in the database that are no longer linked to a valid Event. Use the Repair Tool to easily map them back immediately.</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.events.repair.index') }}" class="ml-4 flex-shrink-0 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-md shadow-sm transition">
                        Launch Repair Tool
                    </a>
                </div>
            </div>
        @endif

            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lecturer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reg's</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Active</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($events as $event)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($event->image)
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $event->image) }}" alt="">
                                                    </div>
                                                @endif
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        <a href="{{ route('admin.events.show', $event) }}" class="hover:text-yemdat-gold hover:underline">
                                                            {{ $event->title_en }}
                                                        </a>
                                                    </div>
                                                    <div class="text-sm text-gray-500">{{ Str::limit(strip_tags($event->description_en), 30) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $event->start_date->format('M d, Y') }}</div>
                                            <div class="text-sm text-gray-500">{{ $event->start_date->format('h:i A') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $event->lecturer_name_en }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-50 text-gray-600 border border-gray-200">
                                                {{ $event->creator->name ?? 'System' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('admin.events.show', $event) }}" class="text-sm font-bold text-yemdat-brown hover:text-yemdat-gold hover:underline" title="View Registered Members">
                                                {{ $event->members()->count() }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $status = $event->status;
                                                $color = $status === 'Upcoming' ? 'bg-blue-100 text-blue-800' : ($status === 'Ongoing' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800');
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                                {{ $status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <form action="{{ route('admin.events.toggle', $event) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="relative inline-flex items-center h-6 rounded-full w-11 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yemdat-gold {{ $event->is_active ? 'bg-green-500' : 'bg-gray-200' }}">
                                                    <span class="sr-only">Toggle status</span>
                                                    <span class="inline-block w-4 h-4 transform bg-white rounded-full transition-transform {{ $event->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <a href="{{ route('admin.events.show', $event) }}" class="text-green-600 hover:text-green-900 border border-green-200 bg-green-50 px-2 py-1 rounded">View</a>
                                                <a href="{{ route('admin.events.edit', $event) }}" class="text-indigo-600 hover:text-indigo-900 border border-indigo-200 bg-indigo-50 px-2 py-1 rounded">Edit</a>
                                                <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="inline-flex m-0 p-0" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 border border-red-200 bg-red-50 px-2 py-1 rounded">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $events->links() }}
                        </div>
                    </div>
                </div>
    </div>
</x-admin-layout>
