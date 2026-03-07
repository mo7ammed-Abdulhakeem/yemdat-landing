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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-6 bg-gray-50 rounded-xl p-6 border border-gray-100 mb-6">
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Email Address</span>
                        <div class="text-sm font-medium text-gray-900" dir="ltr">{{ $trainerRequest->email ?? '-' }}</div>
                    </div>

                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Phone Number</span>
                        <div class="text-sm font-medium text-gray-900" dir="ltr">{{ $trainerRequest->phone_number ?? '-' }}</div>
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

                <!-- Program Type & Duration Highlights -->
                @if($trainerRequest->program_type)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50/50 p-4 rounded-lg border border-blue-100 flex items-center">
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Program Type</p>
                            <p class="text-md font-bold text-gray-900 capitalize">{{ $trainerRequest->program_type }}</p>
                        </div>
                    </div>
                    <div class="bg-emerald-50/50 p-4 rounded-lg border border-emerald-100 flex items-center">
                        <div class="h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 mr-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Duration</p>
                            <div class="flex flex-col text-md font-bold text-gray-900 leading-tight">
                                @if($trainerRequest->duration_days || $trainerRequest->duration_hours)
                                    @if($trainerRequest->duration_days)
                                        <span>{{ $trainerRequest->duration_days }} <span class="text-sm font-medium text-gray-500">Days</span></span>
                                    @endif
                                    @if($trainerRequest->duration_hours)
                                        <span>{{ $trainerRequest->duration_hours }} <span class="text-sm font-medium text-gray-500">Hours</span></span>
                                    @endif
                                @else
                                    <span>-</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="bg-amber-50/50 p-4 rounded-lg border border-amber-100 flex items-center">
                        <div class="h-10 w-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 mr-4">
                            @if($trainerRequest->agreed_to_free_provision)
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Free Provision</p>
                            <p class="text-md font-bold text-gray-900">{{ $trainerRequest->agreed_to_free_provision ? 'Agreed' : 'Not Agreed' }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- The Topic/Help Request -->
                <div class="space-y-4">
                    <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ $trainerRequest->program_type ? 'Program Agenda & Details' : 'Legacy Help Topic' }}</span>
                    <div class="prose prose-sm max-w-none text-gray-700 bg-white border border-gray-100 rounded-xl p-6 shadow-sm leading-relaxed" style="word-break: break-word;">
                        {!! $trainerRequest->agenda ?? ($trainerRequest->getRawOriginal('help_topic') ?? 'No details provided.') !!}
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
