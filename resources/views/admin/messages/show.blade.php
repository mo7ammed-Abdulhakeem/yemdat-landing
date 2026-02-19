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
                            <h3 class="text-lg font-bold text-gray-900">{{ $contact->name }}</h3>
                            <a href="mailto:{{ $contact->email }}" class="text-yemdat-gold hover:text-yemdat-brown text-sm font-medium">{{ $contact->email }}</a>
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
