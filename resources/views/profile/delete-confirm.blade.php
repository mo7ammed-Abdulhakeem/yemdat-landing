<x-app-layout>
    <div class="bg-gray-50 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <x-ui.card padding="p-8" class="max-w-md w-full space-y-8">
            <div>
                <h2 class="text-center text-3xl font-extrabold text-danger">
                    {{ app()->getLocale() == 'ar' ? 'تأكيد الحذف' : 'Confirm Deletion' }}
                </h2>
                <p class="mt-2 text-center text-sm text-ink-soft">
                    {{ app()->getLocale() == 'ar' ? 'لقد أرسلنا رمز تحقق مكون من 6 أرقام إلى بريدك الإلكتروني.' : 'We have sent a 6-digit verification code to your email.' }}
                </p>
            </div>

            @if ($errors->any())
                <x-ui.alert variant="danger">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-ui.alert>
            @endif

            @if (session('success'))
                <x-ui.alert variant="success" class="text-center font-medium">{{ session('success') }}</x-ui.alert>
            @endif

            <form class="space-y-6" action="{{ route('profile.delete.confirm.post') }}" method="POST">
                @csrf
                <div>
                    <label for="otp" class="sr-only">OTP Code</label>
                    <input id="otp" name="otp" type="text" inputmode="numeric" required maxlength="6"
                        class="block w-full rounded-btn border border-border bg-surface-raised px-3 py-3 text-center text-2xl tracking-widest text-ink shadow-sm focus:border-danger focus:outline-none focus:ring-2 focus:ring-danger/40" placeholder="••••••">
                </div>

                <div class="flex items-center">
                    <input id="confirm_deletion" name="confirm_deletion" type="checkbox" required class="h-4 w-4 text-danger focus:ring-danger border-border rounded">
                    <label for="confirm_deletion" class="ms-2 block text-sm text-ink">
                        {{ app()->getLocale() == 'ar' ? 'أفهم أن هذا الإجراء نهائي ولا يمكن التراجع عنه.' : 'I understand this action is permanent and cannot be undone.' }}
                    </label>
                </div>

                <div class="flex gap-4">
                    <x-ui.button variant="outline" :href="route('profile.edit')" class="w-full">
                        {{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}
                    </x-ui.button>
                    <x-ui.button type="submit" variant="danger" class="w-full">
                        {{ app()->getLocale() == 'ar' ? 'حذف الحساب نهائياً' : 'Permanently Delete' }}
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
</x-app-layout>
