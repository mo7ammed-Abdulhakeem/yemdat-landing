<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-12 flex flex-col justify-center sm:px-6 lg:px-8" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <!-- Ensure Yemdat Logo exists or fallback to text -->
            <div class="w-24 h-24 mx-auto bg-white rounded-full flex items-center justify-center p-2 mb-4 shadow-sm border border-gray-100">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Yemdat Logo" class="h-16 w-auto object-contain" onerror="this.outerHTML='<span class=\'text-2xl font-bold text-yemdat-brown\'>Y</span>'">
            </div>
            
            <h2 class="mt-2 text-center text-3xl font-extrabold text-gray-900 leading-tight">
                {{ app()->getLocale() == 'ar' ? 'تسجيل الدخول' : 'Sign in to your account' }}
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                {{ app()->getLocale() == 'ar' ? 'أدخل بياناتك للوصول إلى حسابك' : 'Enter your details to access your account' }}
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-sm sm:rounded-2xl border border-gray-100 sm:px-10">
                
                @if ($errors->any())
                    <div class="mb-4 bg-red-50 text-red-700 p-4 rounded-xl text-sm border border-red-100">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="space-y-6" action="{{ route('public.login.post') }}" method="POST">
                    @csrf
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700">
                            {{ app()->getLocale() == 'ar' ? 'البريد الإلكتروني' : 'Email address' }}
                        </label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-yemdat-gold focus:border-yemdat-brown sm:text-sm">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex justify-between items-center">
                            <label for="password" class="block text-sm font-bold text-gray-700">
                                {{ app()->getLocale() == 'ar' ? 'كلمة المرور' : 'Password' }}
                            </label>
                            <a href="{{ route('password.request') }}" class="text-sm font-bold text-yemdat-brown hover:text-yemdat-gold transition">
                                {{ app()->getLocale() == 'ar' ? 'نسيت كلمة المرور؟' : 'Forgot Password?' }}
                            </a>
                        </div>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-yemdat-gold focus:border-yemdat-brown sm:text-sm">
                        </div>
                    </div>

                    <!-- Submit -->
                    <div>
                        <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-yemdat-brown hover:bg-yemdat-gold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yemdat-brown transition">
                            {{ app()->getLocale() == 'ar' ? 'تسجيل الدخول' : 'Sign in' }}
                        </button>
                    </div>
                </form>

                <div class="mt-6 border-t border-gray-100 pt-6 space-y-4">
                    <!-- Claim Profile -->
                    <div class="bg-gray-50 rounded-xl p-4 text-center border border-gray-100">
                        <p class="text-sm text-gray-600 mb-1">
                            {{ app()->getLocale() == 'ar' ? 'هل أنت عضو قديم وتود المطالبة بملفك؟' : 'Existing member aiming to claim your profile?' }}
                        </p>
                        <a href="{{ route('claim.profile') }}" class="inline-block font-bold text-yemdat-brown hover:text-yemdat-gold transition">
                            {{ app()->getLocale() == 'ar' ? 'المطالبة بحسابك' : 'Claim Profile Here' }} &rarr;
                        </a>
                    </div>
                    
                    <!-- Create New Account -->
                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            {{ app()->getLocale() == 'ar' ? 'ليس لديك حساب؟' : 'Don\'t have an account?' }}
                        </p>
                        <a href="{{ route('register') }}" class="inline-block mt-1 px-6 py-2 border-2 border-yemdat-brown text-yemdat-brown font-bold rounded-xl hover:bg-yemdat-brown hover:text-white transition w-full">
                            {{ app()->getLocale() == 'ar' ? 'إنشاء حساب جديد' : 'Create a New Account' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
