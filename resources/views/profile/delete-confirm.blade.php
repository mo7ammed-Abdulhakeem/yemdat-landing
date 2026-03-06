<x-app-layout>
    <div class="bg-gray-50 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold" style="color: #dc2626;">
                    {{ app()->getLocale() == 'ar' ? 'تأكيد الحذف' : 'Confirm Deletion' }}
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    {{ app()->getLocale() == 'ar' ? 'لقد أرسلنا رمز تحقق مكون من 6 أرقام إلى بريدك الإلكتروني.' : 'We have sent a 6-digit verification code to your email.' }}
                </p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm border border-red-100">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-50 text-green-700 p-4 rounded-xl text-sm border border-green-100 text-center font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <form class="mt-8 space-y-6" action="{{ route('profile.delete.confirm.post') }}" method="POST">
                @csrf
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="otp" class="sr-only">OTP Code</label>
                        <input id="otp" name="otp" type="text" inputmode="numeric" required class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-red-500 focus:border-red-500 focus:z-10 sm:text-sm text-center tracking-widest text-2xl" placeholder="••••••" maxlength="6">
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="confirm_deletion" name="confirm_deletion" type="checkbox" required class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                    <label for="confirm_deletion" class="ml-2 block text-sm text-gray-900 {{ app()->getLocale() == 'ar' ? 'mr-2' : '' }}">
                        {{ app()->getLocale() == 'ar' ? 'أفهم أن هذا الإجراء نهائي ولا يمكن التراجع عنه.' : 'I understand this action is permanent and cannot be undone.' }}
                    </label>
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('profile.edit') }}" class="w-full flex justify-center py-2 px-4 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition">
                        {{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}
                    </a>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-xl text-white transition shadow-sm" style="background-color: #dc2626;">
                        {{ app()->getLocale() == 'ar' ? 'حذف الحساب نهائياً' : 'Permanently Delete' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
