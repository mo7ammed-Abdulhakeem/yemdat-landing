<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-12 flex flex-col justify-center sm:px-6 lg:px-8" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-2 text-center text-3xl font-extrabold text-ink leading-tight">
                @if(isset($isPasswordReset) && $isPasswordReset)
                    {{ app()->getLocale() == 'ar' ? 'إعادة تعيين كلمة المرور' : 'Reset Password' }}
                @else
                    {{ app()->getLocale() == 'ar' ? 'أهلاً،' : 'Welcome,' }} {{ mb_substr($member->full_name, 0, strpos($member->full_name, ' ') ?: null) }}
                @endif
            </h2>
            <p class="mt-2 text-center text-sm text-ink-soft px-4">
                @if(isset($isPasswordReset) && $isPasswordReset)
                    {{ app()->getLocale() == 'ar' ? 'مرحباً '.mb_substr($member->full_name, 0, strpos($member->full_name, ' ') ?: null).'، يرجى إدخال رقم هاتفك كإجراء أمني للإستمرار في تعيين كلمة مرور جديدة.' : 'Hi '.mb_substr($member->full_name, 0, strpos($member->full_name, ' ') ?: null).', please enter your registered phone number as a security measure to set a new password.' }}
                @else
                    {{ app()->getLocale() == 'ar' ? 'لقد وجدنا ملفك! للتحقق من هويتك وإنشاء حسابك، يرجى إدخال رقم هاتفك المسجل ثم تعيين كلمة مرور جديدة.' : 'We found your profile! To verify your identity, please enter your registered phone number and set a new password.' }}
                @endif
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

                <form class="space-y-6" action="{{ route('claim.profile.set-password.post') }}" method="POST">
                    @csrf

                    <!-- Verify Phone Number (Hidden during Password Resets) -->
                    @if(!isset($isPasswordReset) || !$isPasswordReset)
                        <div>
                            <x-ui.input
                                name="phone_number"
                                :label="app()->getLocale() == 'ar' ? 'رقم الهاتف المسجل (للتحقق)' : 'Registered Phone Number (For Verification)'"
                                :required="true"
                                :placeholder="app()->getLocale() == 'ar' ? 'بدون مفتاح الدولة، مثل: 500000000' : 'e.g. 500000000 (without country code)'"
                            />
                            <p class="text-xs text-ink-soft mt-1 font-bold">{{ app()->getLocale() == 'ar' ? '(أدخل الرقم بدون مفتاح الدولة)' : '(Enter the number without country code)' }}</p>
                        </div>
                    @endif

                    <x-ui.input name="password" type="password" :label="app()->getLocale() == 'ar' ? 'كلمة المرور الجديدة' : 'New Password'" :required="true" autocomplete="new-password" />
                    <x-ui.input name="password_confirmation" type="password" :label="app()->getLocale() == 'ar' ? 'تأكيد كلمة المرور' : 'Confirm Password'" :required="true" autocomplete="new-password" />

                    <x-ui.button type="submit" variant="accent" class="w-full">
                        @if(isset($isPasswordReset) && $isPasswordReset)
                            {{ app()->getLocale() == 'ar' ? 'إعادة تعيين كلمة المرور والمتابعة' : 'Reset Password & Continue' }}
                        @else
                            {{ app()->getLocale() == 'ar' ? 'تأكيد وحفظ الملف' : 'Confirm & Claim Profile' }}
                        @endif
                    </x-ui.button>
                </form>
            </x-ui.card>
        </div>
    </div>
</x-app-layout>
