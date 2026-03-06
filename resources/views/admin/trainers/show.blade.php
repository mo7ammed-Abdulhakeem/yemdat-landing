<x-admin-layout>
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.trainers.index') }}" class="text-gray-400 hover:text-yemdat-brown transition-colors">
                    <svg class="w-6 h-6 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h2 class="text-2xl font-bold tracking-tight text-yemdat-brown">Request Details</h2>
            </div>
            
            <form action="{{ route('admin.trainers.destroy', $trainerRequest) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this trainer request?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Delete Request
                </button>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 md:p-8 space-y-8">
                <!-- Header Info -->
                <div class="flex items-start justify-between border-b border-gray-100 pb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $trainerRequest->name }}</h3>
                        <div class="flex items-center gap-4 text-sm text-gray-500">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ $trainerRequest->created_at->format('F j, Y g:i A') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Contact & Location Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-6 bg-gray-50 rounded-xl p-6 border border-gray-100">
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Email Address</span>
                        <div class="text-sm font-medium text-gray-900" dir="ltr">{{ $trainerRequest->email ?? '-' }}</div>
                    </div>

                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Phone Number</span>
                        <div class="text-sm font-medium text-gray-900" dir="ltr">{{ $trainerRequest->phone_number ?? '-' }}</div>
                    </div>

                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Country of Residency</span>
                        <div class="text-sm font-medium text-gray-900">{{ $trainerRequest->country ?: '-' }}</div>
                    </div>

                    @if($trainerRequest->linkedin_url)
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">LinkedIn Profile</span>
                        <div class="text-sm font-medium text-gray-900">
                            <a href="{{ $trainerRequest->linkedin_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1">
                                View Profile
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- The Topic/Help Request -->
                <div class="space-y-4">
                    <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">What can they help with?</span>
                    <div class="prose prose-sm max-w-none text-gray-700 bg-white border border-gray-100 rounded-xl p-6 shadow-sm leading-relaxed" style="word-break: break-word;">
                        {!! $trainerRequest->help_topic !!}
                    </div>
                </div>
                
            </div>
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                <a href="{{ route('admin.trainers.index') }}" class="text-sm font-medium text-gray-600 hover:text-yemdat-brown">
                    &larr; Back to Requests
                </a>
            </div>
        </div>
    </div>
</x-admin-layout>
