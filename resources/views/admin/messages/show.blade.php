<x-admin-layout>
    <x-slot name="header">
        Message Details
    </x-slot>

    <div>
        <div class="mb-6 flex justify-between items-center">
            <a href="{{ route('admin.messages.index') }}" class="text-gray-600 hover:text-yemdat-brown flex items-center gap-1">
                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Messages
            </a>
        </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="p-8 border-b border-gray-100 bg-gray-50/50">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-2xl font-bold text-yemdat-brown">{{ $contact->subject }}</h2>
                            <p class="text-gray-500 text-sm mt-1">Sent on {{ $contact->created_at->format('F d, Y, h:i A') }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <!-- Sender Info -->
                    <div class="mb-8 flex items-center gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="bg-yemdat-brown text-white h-12 w-12 rounded-full flex items-center justify-center font-bold text-xl uppercase">
                            {{ substr($contact->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-lg font-bold text-gray-900">{{ $contact->name }}</h3>
                                @if($contact->member_id)
                                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">Registered Member</span>
                                    <a href="{{ route('admin.members.show', $contact->member_id) }}" class="text-yemdat-gold text-sm font-medium hover:underline flex items-center gap-1">
                                        View Profile
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                @endif
                            </div>
                            <a href="mailto:{{ $contact->email }}" class="text-yemdat-gold hover:text-yemdat-brown text-sm font-medium block">{{ $contact->email }}</a>
                            @if($contact->phone_number)
                                <a href="tel:{{ $contact->phone_number }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium mt-1 inline-flex items-center gap-1" dir="ltr">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1.003 1.003 0 011.02-.24c1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1C10.39 21 3 13.61 3 4c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.24 1.02l-2.2 2.2z"/></svg>
                                    {{ $contact->phone_number }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Message Body -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Message</h3>
                        <div class="prose max-w-none text-gray-900 bg-white p-6 rounded-lg border border-gray-200">
                            {{ $contact->message }}
                        </div>
                    </div>
                </div>
            </div>

    </div>
</x-admin-layout>
