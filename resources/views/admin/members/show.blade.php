<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 flex justify-between items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-yemdat-brown hover:text-yemdat-gold flex items-center gap-2 font-medium">
                    <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Dashboard
                </a>
                <a href="{{ route('admin.members.edit', $member) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yemdat-brown hover:bg-yemdat-gold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yemdat-gold transition-colors">
                    Edit Member
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="p-8 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-yemdat-brown">{{ $member->full_name }}</h2>
                        <p class="text-gray-500 text-sm mt-1">Joined on {{ $member->created_at->format('F d, Y, h:i A') }}</p>
                    </div>
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 capitalize">
                        {{ $member->membershipTier ? $member->membershipTier->name : ucfirst($member->membership_type) }}
                    </span>
                </div>

                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Contact Info -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Contact Information</h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $member->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                                <dd class="mt-1 text-sm text-gray-900" dir="ltr">{{ $member->phone_code }} {{ $member->phone_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Country</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $member->country }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Professional Info -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Professional Details</h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Education Level</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $member->education_level ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Specialty</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $member->specialty === 'other' ? ($member->specialty_other ?? 'Other') : $member->specialty }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
