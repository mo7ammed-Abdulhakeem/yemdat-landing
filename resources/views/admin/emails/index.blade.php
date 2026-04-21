<x-admin-layout>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-3xl font-bold tracking-tight text-yemdat-brown">Email Templates</h2>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl shadow-sm my-4">
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @php
        $labels = [
            'SignupOtpEmail'          => ['name' => 'Signup OTP',            'desc' => 'Sent when a new member registers — contains the email verification code.',       'critical' => true],
            'WelcomeEmail'            => ['name' => 'Welcome Email',          'desc' => 'Sent after a member verifies their email and joins the community.',               'critical' => false],
            'PasswordResetOtpEmail'   => ['name' => 'Password Reset OTP',     'desc' => 'Sent when a member requests a password reset — contains the OTP code.',          'critical' => true],
            'EventConfirmationEmail'  => ['name' => 'Event Confirmation',     'desc' => 'Sent when a member registers for an event — includes .ics calendar attachment.', 'critical' => false],
            'EventReminderEmail'      => ['name' => 'Event Reminder',         'desc' => 'Sent the day before an event to all registered members.',                         'critical' => false],
            'ContactUsAutoReplyEmail' => ['name' => 'Contact Auto-Reply',     'desc' => 'Sent automatically when someone submits the contact form.',                       'critical' => false],
            'TrainerAutoReplyEmail'   => ['name' => 'Trainer Application',    'desc' => 'Sent to trainer applicants confirming receipt of their application.',             'critical' => false],
        ];
        @endphp

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-yemdat-brown/5 text-yemdat-brown">
                            <th class="px-6 py-4 font-bold border-b border-gray-100">Email Type</th>
                            <th class="px-6 py-4 font-bold border-b border-gray-100">Subject (EN)</th>
                            <th class="px-6 py-4 font-bold border-b border-gray-100 text-center">Active</th>
                            <th class="px-6 py-4 font-bold border-b border-gray-100 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($templates as $template)
                        @php $meta = $labels[$template->mailable_class] ?? ['name' => $template->mailable_class, 'desc' => '', 'critical' => false]; @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-800 flex items-center gap-2">
                                    {{ $meta['name'] }}
                                    @if($meta['critical'])
                                        <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-medium">Critical</span>
                                    @endif
                                </div>
                                @if($meta['desc'])
                                    <div class="text-xs text-gray-400 mt-0.5">{{ $meta['desc'] }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600 text-sm truncate max-w-xs">
                                {{ $template->subject_en }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('admin.emails.toggle', $template) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        @if($meta['critical']) title="OTP emails should remain active" @endif
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none
                                            {{ $template->is_active ? 'bg-green-500' : 'bg-gray-300' }}
                                            {{ $meta['critical'] ? 'opacity-60 cursor-not-allowed' : 'cursor-pointer' }}"
                                        {{ $meta['critical'] ? 'disabled' : '' }}>
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform
                                            {{ $template->is_active ? 'translate-x-6' : 'translate-x-1' }}">
                                        </span>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.emails.edit', $template) }}"
                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-yemdat-brown bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                    <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">No email templates found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
