<x-admin-layout>
    <x-slot name="header">
        Email Broadcasts
    </x-slot>

    <div>
        <div class="mb-6 flex justify-between items-center">
            <p class="text-sm text-gray-500">Compose and send bulk emails to members or event registrants.</p>
            <a href="{{ route('admin.broadcasts.create') }}" class="bg-yemdat-brown hover:bg-yemdat-gold text-white font-bold py-2 px-4 rounded-md shadow transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Compose Broadcast
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
            <div class="p-6 bg-white border-b border-gray-200">
                @if($broadcasts->isEmpty())
                    <div class="text-center py-16">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <h3 class="mt-4 text-sm font-medium text-gray-900">No broadcasts yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by composing your first broadcast email.</p>
                        <a href="{{ route('admin.broadcasts.create') }}" class="mt-4 inline-block bg-yemdat-brown text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-yemdat-gold transition">
                            Compose Broadcast
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">Audience</th>
                                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">Lang</th>
                                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">Sent</th>
                                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">Opens</th>
                                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">Unsubs</th>
                                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($broadcasts as $broadcast)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <a href="{{ route('admin.broadcasts.show', $broadcast) }}" class="hover:text-yemdat-gold hover:underline">
                                                    {{ Str::limit($broadcast->subject_en, 45) }}
                                                </a>
                                            </div>
                                            @if($broadcast->subject_ar)
                                                <div class="text-xs text-gray-400 mt-0.5" dir="rtl">{{ Str::limit($broadcast->subject_ar, 45) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            @if($broadcast->audience_type === 'all_members')
                                                <span class="inline-flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                    All Members
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                    {{ $broadcast->event ? Str::limit($broadcast->event->title_en, 25) : 'Event' }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-0.5 text-xs rounded-full {{ $broadcast->language === 'ar' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700' }}">
                                                {{ strtoupper($broadcast->language) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($broadcast->status === 'draft')
                                                <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600">Draft</span>
                                            @elseif($broadcast->status === 'sending')
                                                <span class="px-2 py-0.5 text-xs rounded-full bg-yellow-100 text-yellow-700">Sending</span>
                                            @else
                                                <span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700">Sent</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ number_format($broadcast->total_recipients) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="text-green-700 font-medium">{{ $broadcast->opens_count }}</span>
                                            @if($broadcast->total_recipients > 0)
                                                <span class="text-gray-400 text-xs ml-1">({{ $broadcast->open_rate }}%)</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                                            {{ $broadcast->unsubscribes_count }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $broadcast->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('admin.broadcasts.show', $broadcast) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $broadcasts->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
