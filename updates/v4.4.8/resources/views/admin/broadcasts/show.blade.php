<x-admin-layout>
    <x-slot name="header">
        Broadcast Details
    </x-slot>

    <div>
        <div class="mb-4">
            <a href="{{ route('admin.broadcasts.index') }}" class="text-sm text-yemdat-brown hover:text-yemdat-gold flex items-center gap-1">
                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Broadcasts
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
        @endif

        {{-- Header card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">{{ $broadcast->subject_en }}</h2>
                    @if($broadcast->subject_ar)
                        <p class="text-sm text-gray-500 mt-0.5" dir="rtl">{{ $broadcast->subject_ar }}</p>
                    @endif
                    <div class="flex flex-wrap items-center gap-3 mt-3 text-sm text-gray-500">
                        <span>
                            Audience:
                            <strong>{{ $broadcast->audience_type === 'all_members' ? 'All Members' : 'Event: ' . ($broadcast->event->title_en ?? '—') }}</strong>
                        </span>
                        <span>Language: <strong>{{ strtoupper($broadcast->language) }}</strong></span>
                        <span>Sent by: <strong>{{ $broadcast->creator->name ?? '—' }}</strong></span>
                        @if($broadcast->sent_at)
                            <span>Sent: <strong>{{ $broadcast->sent_at->format('M d, Y H:i') }}</strong></span>
                        @endif
                        @if($broadcast->from_email)
                            <span>From: <strong>{{ $broadcast->from_email }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    @if($broadcast->status === 'draft')
                        <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-600 font-medium">Draft</span>
                        <a href="{{ route('admin.broadcasts.edit', $broadcast) }}"
                           class="border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-2 px-4 rounded-md text-sm transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </a>
                        <form action="{{ route('admin.broadcasts.send', $broadcast) }}" method="POST"
                            onsubmit="return confirm('Send this broadcast to {{ $broadcast->audience_type === 'all_members' ? 'all subscribed members' : 'event registrants' }}? This cannot be undone.');">
                            @csrf
                            <button type="submit" class="bg-yemdat-brown hover:bg-yemdat-gold text-white font-bold py-2 px-5 rounded-md shadow transition text-sm flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                Send Now
                            </button>
                        </form>
                    @elseif($broadcast->status === 'sending')
                        <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700 font-medium">Sending…</span>
                    @else
                        <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700 font-medium">Sent</span>
                        @if($broadcast->audience_type === 'event_members')
                            <form action="{{ route('admin.broadcasts.send-new', $broadcast) }}" method="POST"
                                  onsubmit="return confirm('Send this broadcast only to new registrants who have not received it yet?');">
                                @csrf
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md text-sm transition">
                                    Send to New Registrants
                                </button>
                            </form>
                        @endif
                    @endif
                    <form action="{{ route('admin.broadcasts.destroy', $broadcast) }}" method="POST"
                          onsubmit="return confirm('Permanently delete this broadcast and all {{ number_format($broadcast->total_recipients) }} recipient records? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="border border-red-200 hover:bg-red-50 text-red-600 font-bold py-2 px-4 rounded-md text-sm transition">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sending progress banner --}}
        @if($broadcast->status === 'sending')
            <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 rounded-xl px-5 py-4 text-sm">
                Sending in daily batches (90/day). <strong>{{ $stats['sent'] }} of {{ $stats['total'] }}</strong> sent so far. Next batch runs automatically in ~24 hours.
            </div>
        @endif

        {{-- Stat cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Queued</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Sent So Far</p>
                <p class="text-3xl font-bold text-gray-700">{{ number_format($stats['sent']) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Opened</p>
                <p class="text-3xl font-bold text-green-700">{{ number_format($stats['opens']) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Open Rate</p>
                <p class="text-3xl font-bold text-blue-700">{{ $stats['open_rate'] }}%</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Unsubscribes</p>
                <p class="text-3xl font-bold text-red-600">{{ number_format($stats['unsubscribes']) }}</p>
            </div>
        </div>

        {{-- Recipients table --}}
        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900">Recipients</h3>
            </div>
            <div class="overflow-x-auto">
                @if($recipients->isEmpty())
                    <div class="text-center py-12 text-sm text-gray-400">No recipients yet — send this broadcast to populate the list.</div>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">Opened</th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">Opens</th>
                                <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">Unsubscribed</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recipients as $recipient)
                                <tr>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $recipient->member->full_name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">
                                        {{ $recipient->email }}
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm">
                                        @if($recipient->opened_at)
                                            <span class="text-green-700">{{ $recipient->opened_at->format('M d, H:i') }}</span>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700">
                                        {{ $recipient->open_count > 0 ? $recipient->open_count : '—' }}
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm">
                                        @if($recipient->unsubscribed_at)
                                            <span class="text-red-600">{{ $recipient->unsubscribed_at->format('M d, H:i') }}</span>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="px-6 py-4">
                        {{ $recipients->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
