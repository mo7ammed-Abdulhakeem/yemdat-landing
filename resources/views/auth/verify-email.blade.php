<x-app-layout>
    <x-slot name="title">Verify Email | Yemdat</x-slot>

    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-primary">
                {{ app()->getLocale() == 'ar' ? 'تأكيد بريدك الإلكتروني' : 'Verify Your Email' }}
            </h2>
            <p class="mt-2 text-center text-sm text-ink-soft">
                {{ app()->getLocale() == 'ar' ? 'لقد أرسلنا رمز تحقق مكون من 6 أرقام إلى بريدك الإلكتروني.' : 'We have sent a 6-digit OTP code to your email address.' }}
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <x-ui.card padding="py-8 px-4 sm:px-10">
                @if (session('success'))
                    <x-ui.alert variant="success" class="mb-4 text-center font-bold">{{ session('success') }}</x-ui.alert>
                @endif
                @if (session('error'))
                    <x-ui.alert variant="danger" class="mb-4 text-center font-bold">{{ session('error') }}</x-ui.alert>
                @endif

                <form class="space-y-6" action="{{ route('verification.verify') }}" method="POST">
                    @csrf
                    <div>
                        <x-ui.label for="otp">{{ app()->getLocale() == 'ar' ? 'رمز التحقق (6 أرقام)' : '6-Digit OTP Code' }}</x-ui.label>
                        <input id="otp" name="otp" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="6" autocomplete="one-time-code" required
                            class="mt-1 block w-full rounded-btn border border-border bg-surface-raised px-4 py-3 text-center text-2xl font-bold tracking-[0.5em] text-ink shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-accent/50" placeholder="------">
                        @error('otp')
                            <p class="mt-2 text-sm text-danger font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-ui.button type="submit" class="w-full uppercase tracking-wider">
                        {{ app()->getLocale() == 'ar' ? 'تأكيد الحساب' : 'Verify Account' }}
                    </x-ui.button>
                </form>

                <div class="mt-6 flex items-center justify-between">
                    <span class="text-sm text-ink-soft">{{ app()->getLocale() == 'ar' ? 'لم تستلم الرمز؟' : 'Did not receive the code?' }}</span>
                    <form action="{{ route('verification.resend') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm font-bold text-primary hover:text-accent-hover transition-colors">
                            {{ app()->getLocale() == 'ar' ? 'إعادة الإرسال' : 'Resend OTP' }}
                        </button>
                    </form>
                </div>
            </x-ui.card>
        </div>
    </div>
</x-app-layout>
