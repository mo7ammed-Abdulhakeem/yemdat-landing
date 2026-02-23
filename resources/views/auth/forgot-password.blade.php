<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-12 flex flex-col justify-center sm:px-6 lg:px-8" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <!-- Ensure Yemdat Logo exists or fallback to text -->
            <div class="w-24 h-24 mx-auto bg-white rounded-full flex items-center justify-center p-2 mb-4 shadow-sm border border-gray-100">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Yemdat Logo" class="h-16 w-auto object-contain" onerror="this.outerHTML='<span class=\'text-2xl font-bold text-yemdat-brown\'>Y</span>'">
            </div>
            
            <h2 class="mt-2 text-center text-3xl font-extrabold text-gray-900 leading-tight">
                {{ app()->getLocale() == 'ar' ? 'استعادة كلمة المرور' : 'Recover Password' }}
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 px-4">
                {{ app()->getLocale() == 'ar' ? 'أدخل بريدك الإلكتروني ورقم هاتفك المسجل (بدون مفتاح الدولة) للتحقق من هويتك وتعيين كلمة مرور جديدة.' : 'Enter your registered email and phone number (without country code) to verify your identity and set a new password.' }}
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

                <form class="space-y-6" action="{{ route('password.verify') }}" method="POST">
                    @csrf
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700">
                            {{ app()->getLocale() == 'ar' ? 'البريد الإلكتروني' : 'Registered Email Address' }}
                        </label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-yemdat-gold focus:border-yemdat-brown sm:text-sm">
                        </div>
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="phone_number" class="block text-sm font-bold text-gray-700">
                            {{ app()->getLocale() == 'ar' ? 'رقم الهاتف المسجل' : 'Registered Phone Number' }}
                        </label>
                        <div class="mt-1 cursor-not-allowed">
                            <input id="phone_number" name="phone_number" type="text" required value="{{ old('phone_number') }}"
                                placeholder="{{ app()->getLocale() == 'ar' ? 'بدون مفتاح الدولة، مثل: 500000000' : 'e.g. 500000000 (without country code)' }}"
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-yemdat-gold focus:border-yemdat-brown sm:text-sm">
                        </div>
                        <p class="text-xs text-gray-400 mt-1 font-bold">{{ app()->getLocale() == 'ar' ? '(أدخل الرقم بدون مفتاح الدولة)' : '(Enter the number without country code)' }}</p>
                    </div>

                    <!-- Submit -->
                    <div>
                        <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-yemdat-brown hover:bg-yemdat-gold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yemdat-brown transition">
                            {{ app()->getLocale() == 'ar' ? 'التحقق والمتابعة' : 'Verify & Continue' }} &rarr;
                        </button>
                    </div>
                </form>

                <div class="mt-6 border-t border-gray-100 pt-6">
                    <p class="text-center text-sm text-gray-600">
                        <a href="{{ route('public.login') }}" class="font-bold text-yemdat-brown hover:text-yemdat-gold transition">
                            &larr; {{ app()->getLocale() == 'ar' ? 'العودة لتسجيل الدخول' : 'Back to Login' }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
