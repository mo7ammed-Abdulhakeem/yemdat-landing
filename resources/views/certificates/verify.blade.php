<x-app-layout>
    @php($isAr = app()->getLocale() === 'ar')
    <div class="bg-gray-50 min-h-screen py-16" dir="{{ $isAr ? 'rtl' : 'ltr' }}">
        <div class="max-w-xl mx-auto px-4">

            <div class="text-center mb-8">
                <h1 class="text-2xl font-extrabold text-yemdat-brown">
                    {{ $isAr ? 'التحقق من الشهادة' : 'Certificate Verification' }}
                </h1>
                <p class="text-gray-500 mt-1 text-sm">
                    {{ $isAr ? 'مجتمع يمدات للبيانات' : 'Yemdat Data Community' }}
                </p>
            </div>

            @if(! $certificate)
                {{-- Not found --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="h-2 bg-gray-300"></div>
                    <div class="p-8 text-center">
                        <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 mb-1">
                            {{ $isAr ? 'لم يتم العثور على الشهادة' : 'Certificate not found' }}
                        </h2>
                        <p class="text-gray-500">
                            {{ $isAr ? 'لا توجد شهادة بهذا الرقم التسلسلي.' : 'No certificate matches this serial number.' }}
                        </p>
                        <p class="mt-4 text-xs text-gray-400" dir="ltr">{{ $serial }}</p>
                    </div>
                </div>
            @elseif($certificate->revoked_at)
                {{-- Revoked --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="h-2 bg-red-500"></div>
                    <div class="p-8 text-center">
                        <div class="w-16 h-16 mx-auto rounded-full bg-red-50 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                        </div>
                        <h2 class="text-xl font-bold text-red-600 mb-1">
                            {{ $isAr ? 'شهادة ملغاة' : 'Certificate revoked' }}
                        </h2>
                        <p class="text-gray-500">
                            {{ $isAr ? 'تم إلغاء هذه الشهادة من قبل الجهة المصدرة.' : 'This certificate has been revoked by the issuing authority.' }}
                        </p>
                    </div>
                    @include('certificates._details', ['certificate' => $certificate, 'isAr' => $isAr])
                </div>
            @else
                {{-- Valid --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="h-2 bg-gradient-to-r from-yemdat-brown via-yemdat-gold to-yemdat-brown"></div>
                    <div class="p-8 text-center">
                        <div class="w-16 h-16 mx-auto rounded-full bg-green-50 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h2 class="text-xl font-bold text-green-600 mb-1">
                            {{ $isAr ? 'شهادة صحيحة' : 'Valid certificate' }}
                        </h2>
                        <p class="text-gray-500">
                            {{ $isAr ? 'هذه شهادة إتمام أصلية صادرة عن مجتمع يمدات.' : 'This is an authentic Certificate of Completion issued by Yemdat.' }}
                        </p>
                    </div>
                    @include('certificates._details', ['certificate' => $certificate, 'isAr' => $isAr])
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
