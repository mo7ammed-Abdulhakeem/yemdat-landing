<x-app-layout>
    <x-slot name="title">Verify OTP | Yemdat</x-slot>

    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-yemdat-brown">
                {{ app()->getLocale() == 'ar' ? 'تحقق من الرمز' : 'Verify OTP' }}
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 px-4">
                {{ app()->getLocale() == 'ar' ? 'لقد أرسلنا رمز تحقق (OTP) إلى بريدك الإلكتروني. يرجى إدخاله للمتابعة وتعيين كلمة مرور جديدة.' : 'We have sent an OTP to your email. Please enter it to continue and set a new password.' }}
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-2xl sm:px-10 border border-gray-100 relative">
                
                @if ($errors->any())
                    <div class="mb-4 bg-red-50 text-red-700 p-4 rounded-xl text-sm border border-red-100">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if (session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm relative text-center">
                        <span class="block sm:inline font-bold">{{ session('success') }}</span>
                    </div>
                @endif
                
                <form class="space-y-6" action="{{ route('password.verify.otp.post') }}" method="POST">
                    @csrf
                    
                    <div>
                        <label for="otp" class="block text-sm font-medium text-gray-700">{{ app()->getLocale() == 'ar' ? 'رمز التحقق (6 أرقام)' : '6-Digit OTP Code' }}</label>
                        <div class="mt-1">
                            <input id="otp" name="otp" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="6" autocomplete="one-time-code" required class="block w-full appearance-none rounded-xl border border-gray-300 px-4 py-3 text-center text-2xl font-bold tracking-[0.5em] placeholder-gray-400 shadow-sm focus:border-yemdat-brown focus:outline-none focus:ring-yemdat-gold sm:text-2xl" placeholder="------">
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="flex w-full justify-center rounded-xl border border-transparent bg-yemdat-brown py-3 px-4 text-sm font-bold text-white shadow-sm hover:bg-yemdat-gold hover:text-yemdat-brown transition-colors focus:outline-none focus:ring-2 focus:ring-yemdat-gold focus:ring-offset-2 uppercase tracking-wider">
                            {{ app()->getLocale() == 'ar' ? 'المتابعة للتعيين' : 'Verify & Set Password' }}
                        </button>
                    </div>
                </form>

                <div class="mt-6 border-t border-gray-100 pt-6">
                    <p class="text-center text-sm text-gray-600">
                        <a href="{{ route('password.request') }}" class="font-bold text-yemdat-brown hover:text-yemdat-gold transition">
                            &larr; {{ app()->getLocale() == 'ar' ? 'العودة' : 'Go Back' }}
                        </a>
                    </p>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
