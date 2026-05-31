<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-12 flex flex-col justify-center sm:px-6 lg:px-8" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <!-- Ensure Yemdat Logo exists or fallback to text -->
            <div class="w-24 h-24 mx-auto bg-white rounded-full flex items-center justify-center p-2 mb-4 shadow-sm border border-gray-100">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Yemdat Logo" class="h-16 w-auto object-contain" onerror="this.outerHTML='<span class=\'text-2xl font-bold text-yemdat-brown\'>Y</span>'">
            </div>

            <h2 class="mt-2 text-center text-3xl font-extrabold text-ink leading-tight">
                {{ app()->getLocale() == 'ar' ? 'استعادة كلمة المرور' : 'Recover Password' }}
            </h2>
            <p class="mt-2 text-center text-sm text-ink-soft px-4">
                {{ app()->getLocale() == 'ar' ? 'أدخل بريدك الإلكتروني المسجل وسنرسل لك رمز تحقق (OTP) لتعيين كلمة مرور جديدة.' : 'Enter your registered email and we will send you an OTP to securely set a new password.' }}
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <x-ui.card padding="py-8 px-4 sm:px-10">
                @if ($errors->any())
                    <x-ui.alert variant="danger" class="mb-6">
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-ui.alert>
                @endif

                <form class="space-y-6" action="{{ route('password.verify') }}" method="POST">
                    @csrf

                    <x-ui.input
                        name="email"
                        type="email"
                        autocomplete="email"
                        :label="app()->getLocale() == 'ar' ? 'البريد الإلكتروني' : 'Registered Email Address'"
                        :required="true"
                    />

                    <x-ui.button type="submit" class="w-full">
                        {{ app()->getLocale() == 'ar' ? 'إرسال الرمز' : 'Send OTP' }} &rarr;
                    </x-ui.button>
                </form>

                <div class="mt-6 border-t border-gray-100 pt-6">
                    <p class="text-center text-sm text-ink-soft">
                        <a href="{{ route('public.login') }}" class="font-bold text-primary hover:text-accent-hover transition">
                            &larr; {{ app()->getLocale() == 'ar' ? 'العودة لتسجيل الدخول' : 'Back to Login' }}
                        </a>
                    </p>
                </div>
            </x-ui.card>
        </div>
    </div>
</x-app-layout>
