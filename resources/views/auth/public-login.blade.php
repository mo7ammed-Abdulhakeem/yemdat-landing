<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-12 flex flex-col justify-center sm:px-6 lg:px-8" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <!-- Ensure Yemdat Logo exists or fallback to text -->
            <div class="w-24 h-24 mx-auto bg-white rounded-full flex items-center justify-center p-2 mb-4 shadow-sm border border-gray-100">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Yemdat Logo" class="h-16 w-auto object-contain" onerror="this.outerHTML='<span class=\'text-2xl font-bold text-yemdat-brown\'>Y</span>'">
            </div>

            <h2 class="mt-2 text-center text-3xl font-extrabold text-ink leading-tight">
                {{ app()->getLocale() == 'ar' ? 'تسجيل الدخول' : 'Sign in to your account' }}
            </h2>
            <p class="mt-2 text-center text-sm text-ink-soft">
                {{ app()->getLocale() == 'ar' ? 'أدخل بياناتك للوصول إلى حسابك' : 'Enter your details to access your account' }}
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

                <form class="space-y-6" action="{{ route('public.login.post') }}" method="POST">
                    @csrf

                    <x-ui.input
                        name="email"
                        type="email"
                        autocomplete="email"
                        :label="app()->getLocale() == 'ar' ? 'البريد الإلكتروني' : 'Email address'"
                        :required="true"
                    />

                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label for="password" class="block text-sm font-medium text-ink">
                                {{ app()->getLocale() == 'ar' ? 'كلمة المرور' : 'Password' }}
                            </label>
                            <a href="{{ route('password.request') }}" class="text-sm font-bold text-primary hover:text-accent-hover transition">
                                {{ app()->getLocale() == 'ar' ? 'نسيت كلمة المرور؟' : 'Forgot Password?' }}
                            </a>
                        </div>
                        <x-ui.input name="password" type="password" autocomplete="current-password" :required="true" />
                    </div>

                    <x-ui.button type="submit" class="w-full">
                        {{ app()->getLocale() == 'ar' ? 'تسجيل الدخول' : 'Sign in' }}
                    </x-ui.button>
                </form>

                <div class="mt-6 border-t border-gray-100 pt-6 space-y-4">
                    <!-- Claim Profile -->
                    <div class="bg-gray-50 rounded-xl p-4 text-center border border-gray-100">
                        <p class="text-sm text-ink-soft mb-1">
                            {{ app()->getLocale() == 'ar' ? 'هل أنت عضو قديم وتود المطالبة بملفك؟' : 'Existing member aiming to claim your profile?' }}
                        </p>
                        <a href="{{ route('claim.profile') }}" class="inline-block font-bold text-primary hover:text-accent-hover transition">
                            {{ app()->getLocale() == 'ar' ? 'المطالبة بحسابك' : 'Claim Profile Here' }} &rarr;
                        </a>
                    </div>

                    <!-- Create New Account -->
                    <div class="text-center">
                        <p class="text-sm text-ink-soft mb-1">
                            {{ app()->getLocale() == 'ar' ? 'ليس لديك حساب؟' : 'Don\'t have an account?' }}
                        </p>
                        <x-ui.button variant="outline" :href="route('register')" class="w-full">
                            {{ app()->getLocale() == 'ar' ? 'إنشاء حساب جديد' : 'Create a New Account' }}
                        </x-ui.button>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>
</x-app-layout>
