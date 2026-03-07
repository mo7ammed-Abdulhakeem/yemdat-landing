<x-app-layout>
    <x-slot name="title">Local Email Testing</x-slot>

    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-yemdat-brown">Captured Emails (Local Only)</h1>
                <p class="text-sm text-gray-500 mt-1">This inbox intercepts emails and displays them securely during local testing.</p>
            </div>
            <form action="{{ route('testemail.clear') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-50 text-red-600 border border-red-200 px-4 py-2 rounded-lg font-bold shadow-sm hover:bg-red-500 hover:text-white transition">Clear Inbox</button>
            </form>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 text-sm font-bold rounded-lg relative">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-sm border border-gray-100 overflow-hidden sm:rounded-xl">
            @if(count($emails) > 0)
                <ul class="divide-y divide-gray-100">
                    @foreach($emails as $email)
                    <li class="hover:bg-gray-50 transition">
                        <div class="px-6 py-5 flex items-center justify-between">
                            <div class="flex-1 min-w-0 pr-4">
                                <p class="text-lg font-bold text-gray-900 truncate">{{ $email['subject'] }}</p>
                                <p class="text-sm text-gray-600 mt-1">To: <span class="font-bold text-yemdat-brown">{{ $email['to'] }}</span></p>
                                <p class="text-xs text-gray-400 mt-1 font-mono object-cover">{{ $email['date'] }}</span></p>
                            </div>
                            <div>
                                <a href="{{ route('testemail.show', $email['id']) }}" target="_blank" class="inline-flex items-center shadow-sm px-4 py-2 border border-gray-300 text-sm font-bold rounded-xl text-gray-700 bg-white hover:bg-yemdat-brown hover:text-white hover:border-transparent transition-colors">
                                    View Source HTML
                                </a>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            @else
                <div class="p-12 text-center flex flex-col items-center">
                    <div class="h-16 w-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mb-4 border border-gray-200 shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-gray-500 font-medium">No emails captured yet. Perform an action (like registering) to see them here!</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
