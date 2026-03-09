<x-admin-layout>
    <x-slot name="header">
        Repair Orphaned Event Registrations
    </x-slot>

    <div>
        <div class="mb-6 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.events.index') }}" class="text-yemdat-brown hover:text-yemdat-gold flex items-center gap-1 font-medium text-sm">
                    <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Events
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        
        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
            <div class="p-6 bg-white border-b border-gray-200">
                
                <h3 class="text-lg font-bold text-gray-900 mb-4">Database Analysis</h3>
                <p class="text-sm text-gray-600 mb-6 border-l-4 border-yellow-400 pl-4 bg-yellow-50 py-3 rounded-r-md">
                    We detected <strong>{{ count($orphanedGroups) }}</strong> groups of registrations pointing to an old UUID that no longer exists in the Database (usually caused by a migration failure rollback over the Event table). <br><br>
                    Review the "Total Regs" and "Dates", and select which active Event you want to map them into!
                </p>

                @if(count($orphanedGroups) > 0)
                    <form action="{{ route('admin.events.repair.store') }}" method="POST">
                        @csrf
                        <div class="overflow-x-auto mb-6">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Missing UUID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Reg's</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">First Registration</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Registration</th>
                                        <th class="px-6 py-3 text-left text-xs text-yemdat-brown font-bold uppercase tracking-wider border-l-2 border-yemdat-gold bg-yemdat-gold/10">Map To Live Event -></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($orphanedGroups as $group)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-400 font-mono">
                                                {{ Str::limit($group->event_id, 13) }}...
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-800">
                                                    {{ $group->total_members }} Members
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($group->earliest_reg)->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($group->latest_reg)->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-l-2 border-yemdat-gold/20">
                                                <select name="mappings[{{ $group->event_id }}]" class="mt-1 block w-full pl-3 pr-10 py-2 border-gray-300 focus:outline-none focus:ring-yemdat-gold focus:border-yemdat-gold sm:text-sm rounded-md shadow-sm">
                                                    <option value="">-- Ignore Group --</option>
                                                    @foreach($liveEvents as $live)
                                                        <option value="{{ $live->id }}">[{{ $live->start_date->format('M d') }}] {{ $live->title_en }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex justify-end pr-4">
                            <button type="submit" class="bg-yemdat-brown hover:bg-yemdat-gold text-white font-bold py-3 px-8 rounded-xl shadow-lg transition flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Save & Repair Database Relationships
                            </button>
                        </div>
                    </form>
                @else
                    <div class="text-center py-10">
                        <svg class="mx-auto h-12 w-12 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Database perfectly healthy!</h3>
                        <p class="mt-1 text-sm text-gray-500">No orphaned registrations were found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
