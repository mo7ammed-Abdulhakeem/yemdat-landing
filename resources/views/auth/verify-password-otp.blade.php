<x-app-layout>
    <x-slot name="title">Verify OTP | Yemdat</x-slot>

    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-primary">
                {{ app()->getLocale() == 'ar' ? 'تحقق من الرمز' : 'Verify OTP' }}
            </h2>
            <p class="mt-2 text-center text-sm text-ink-soft px-4">
                {{ app()->getLocale() == 'ar' ? 'لقد أرسلنا رمز تحقق (OTP) إلى بريدك الإلكتروني. يرجى إدخاله للمتابعة وتعيين كلمة مرور جديدة.' : 'We have sent an OTP to your email. Please enter it to continue and set a new password.' }}
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <x-ui.card padding="py-8 px-4 sm:px-10">
                @if ($errors->any())
                    <x-ui.alert variant="danger" class="mb-4">
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-ui.alert>
                @endif
                @if (session('success'))
                    <x-ui.alert variant="success" class="mb-4 text-center font-bold">{{ session('success') }}</x-ui.alert>
                @endif

                <form class="space-y-6" action="{{ route('password.verify.otp.post') }}" method="POST">
                    @csrf
                    <div>
                        <x-ui.label for="otp">{{ app()->getLocale() == 'ar' ? 'رمز التحقق (6 أرقام)' : '6-Digit OTP Code' }}</x-ui.label>
                        <input id="otp" name="otp" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="6" autocomplete="one-time-code" required
                            class="mt-1 block w-full rounded-btn border border-border bg-surface-raised px-4 py-3 text-center text-2xl font-bold tracking-[0.5em] text-ink shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-accent/50" placeholder="------">
                    </div>

                    <x-ui.button type="submit" class="w-full uppercase tracking-wider">
                        {{ app()->getLocale() == 'ar' ? 'المتابعة للتعيين' : 'Verify & Set Password' }}
                    </x-ui.button>
                </form>

                <div class="mt-6 border-t border-gray-100 pt-6">
                    <p class="text-center text-sm text-ink-soft">
                        <a href="{{ route('password.request') }}" class="font-bold text-primary hover:text-accent-hover transition">
                            &larr; {{ app()->getLocale() == 'ar' ? 'العودة' : 'Go Back' }}
                        </a>
                    </p>
                </div>
            </x-ui.card>
        </div>
    </div>
</x-app-layout>
