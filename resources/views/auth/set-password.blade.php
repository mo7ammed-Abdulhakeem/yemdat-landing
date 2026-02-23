<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-12 flex flex-col justify-center sm:px-6 lg:px-8" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-2 text-center text-3xl font-extrabold text-gray-900 leading-tight">
                @if(isset($isPasswordReset) && $isPasswordReset)
                    {{ app()->getLocale() == 'ar' ? 'إعادة تعيين كلمة المرور' : 'Reset Password' }}
                @else
                    {{ app()->getLocale() == 'ar' ? 'أهلاً،' : 'Welcome,' }} {{ mb_substr($member->full_name, 0, strpos($member->full_name, ' ') ?: null) }}
                @endif
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 px-4">
                @if(isset($isPasswordReset) && $isPasswordReset)
                    {{ app()->getLocale() == 'ar' ? 'مرحباً '.mb_substr($member->full_name, 0, strpos($member->full_name, ' ') ?: null).'، يرجى إدخال رقم هاتفك كإجراء أمني للإستمرار في تعيين كلمة مرور جديدة.' : 'Hi '.mb_substr($member->full_name, 0, strpos($member->full_name, ' ') ?: null).', please enter your registered phone number as a security measure to set a new password.' }}
                @else
                    {{ app()->getLocale() == 'ar' ? 'لقد وجدنا ملفك! للتحقق من هويتك وإنشاء حسابك، يرجى إدخال رقم هاتفك المسجل ثم تعيين كلمة مرور جديدة.' : 'We found your profile! To verify your identity, please enter your registered phone number and set a new password.' }}
                @endif
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

                <form class="space-y-6" action="{{ route('claim.profile.set-password.post') }}" method="POST">
                    @csrf
                    
                    <!-- Verify Phone Number -->
                    <div>
                        <label for="phone_number" class="block text-sm font-bold text-gray-700">
                            {{ app()->getLocale() == 'ar' ? 'رقم الهاتف المسجل (للتحقق)' : 'Registered Phone Number (For Verification)' }}
                        </label>
                        <div class="mt-1">
                            <input id="phone_number" name="phone_number" type="text" required value="{{ old('phone_number') }}"
                                placeholder="{{ app()->getLocale() == 'ar' ? 'بدون مفتاح الدولة، مثل: 500000000' : 'e.g. 500000000 (without country code)' }}"
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-yemdat-gold focus:border-yemdat-brown sm:text-sm">
                        </div>
                        <p class="text-xs text-gray-400 mt-1 font-bold">{{ app()->getLocale() == 'ar' ? '(أدخل الرقم بدون مفتاح الدولة)' : '(Enter the number without country code)' }}</p>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-bold text-gray-700">
                            {{ app()->getLocale() == 'ar' ? 'كلمة المرور الجديدة' : 'New Password' }}
                        </label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-yemdat-gold focus:border-yemdat-brown sm:text-sm">
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-gray-700">
                            {{ app()->getLocale() == 'ar' ? 'تأكيد كلمة المرور' : 'Confirm Password' }}
                        </label>
                        <div class="mt-1">
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-yemdat-gold focus:border-yemdat-brown sm:text-sm">
                        </div>
                    </div>

                    <!-- Submit -->
                    <div>
                        <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-yemdat-gold hover:bg-yemdat-brown focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yemdat-gold transition">
                            @if(isset($isPasswordReset) && $isPasswordReset)
                                {{ app()->getLocale() == 'ar' ? 'إعادة تعيين كلمة المرور والمتابعة' : 'Reset Password & Continue' }}
                            @else
                                {{ app()->getLocale() == 'ar' ? 'تأكيد وحفظ الملف' : 'Confirm & Claim Profile' }}
                            @endif
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
