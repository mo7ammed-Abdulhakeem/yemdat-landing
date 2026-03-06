<x-app-layout>
    <x-slot name="title">Verify Email | Yemdat</x-slot>

    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-yemdat-brown">
                {{ app()->getLocale() == 'ar' ? 'تأكيد بريدك الإلكتروني' : 'Verify Your Email' }}
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                {{ app()->getLocale() == 'ar' ? 'لقد أرسلنا رمز تحقق مكون من 6 أرقام إلى بريدك الإلكتروني.' : 'We have sent a 6-digit OTP code to your email address.' }}
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-2xl sm:px-10 border border-gray-100 relative">
                
                @if (session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm relative text-center">
                        <span class="block sm:inline font-bold">{{ session('success') }}</span>
                    </div>
                @endif
                
                @if (session('error'))
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm relative text-center">
                        <span class="block sm:inline font-bold">{{ session('error') }}</span>
                    </div>
                @endif

                <form class="space-y-6" action="{{ route('verification.verify') }}" method="POST">
                    @csrf
                    
                    <div>
                        <label for="otp" class="block text-sm font-medium text-gray-700">{{ app()->getLocale() == 'ar' ? 'رمز التحقق (6 أرقام)' : '6-Digit OTP Code' }}</label>
                        <div class="mt-1">
                            <input id="otp" name="otp" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="6" autocomplete="one-time-code" required class="block w-full appearance-none rounded-xl border border-gray-300 px-4 py-3 text-center text-2xl font-bold tracking-[0.5em] placeholder-gray-400 shadow-sm focus:border-yemdat-brown focus:outline-none focus:ring-yemdat-gold sm:text-2xl" placeholder="------">
                        </div>
                        @error('otp')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit" class="flex w-full justify-center rounded-xl border border-transparent bg-yemdat-brown py-3 px-4 text-sm font-bold text-white shadow-sm hover:bg-yemdat-gold hover:text-yemdat-brown transition-colors focus:outline-none focus:ring-2 focus:ring-yemdat-gold focus:ring-offset-2 uppercase tracking-wider">
                            {{ app()->getLocale() == 'ar' ? 'تأكيد الحساب' : 'Verify Account' }}
                        </button>
                    </div>
                </form>

                <div class="mt-6 flex items-center justify-between">
                    <div class="text-sm">
                        <span class="text-gray-500">{{ app()->getLocale() == 'ar' ? 'لم تستلم الرمز؟' : 'Did not receive the code?' }}</span>
                    </div>
                    <div class="text-sm">
                        <form action="{{ route('verification.resend') }}" method="POST">
                            @csrf
                            <button type="submit" class="font-bold text-yemdat-brown hover:text-yemdat-gold transition-colors">
                                {{ app()->getLocale() == 'ar' ? 'إعادة الإرسال' : 'Resend OTP' }}
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
