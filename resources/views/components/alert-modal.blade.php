@props(['type' => 'success', 'message'])

@php
    $isSuccess = $type === 'success';
    $iconColor = $isSuccess ? 'text-green-500' : 'text-red-500';
    $bgColor = $isSuccess ? 'bg-green-50' : 'bg-red-50';
    $btnColor = $isSuccess ? 'bg-green-50 text-green-700 border-green-100 hover:bg-green-500 hover:text-white hover:border-green-500' : 'bg-red-50 text-red-700 border-red-100 hover:bg-red-500 hover:text-white hover:border-red-500';
    $gradient = $isSuccess ? 'from-green-400 to-green-500' : 'from-red-400 to-red-500';
    $pulseColor = $isSuccess ? 'bg-green-100' : 'bg-red-100';
    $titleText = $isSuccess ? (app()->getLocale() == 'ar' ? 'تم بنجاح!' : 'Success!') : (app()->getLocale() == 'ar' ? 'حدث خطأ!' : 'Error!');
    $btnText = app()->getLocale() == 'ar' ? 'حسناً، إغلاق' : 'Okay, Close';
@endphp

<div 
    x-data="{ show: true }"
    x-show="show"
    x-transition.opacity.duration.300ms
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4"
    style="display: none;"
>
    <div 
        x-show="show"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 scale-90 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        @click.away="show = false"
        class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 text-center relative overflow-hidden"
    >
        <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r {{ $gradient }}"></div>
        
        <!-- Close Button -->
        <button type="button" @click="show = false" class="absolute top-4 right-4 rtl:left-4 rtl:right-auto text-gray-400 hover:text-gray-600 transition bg-gray-50 rounded-full p-1 border border-gray-100 shadow-sm hover:shadow">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        
        <!-- Icon -->
        <div class="w-20 h-20 {{ $bgColor }} rounded-full flex items-center justify-center mx-auto mb-5 border-4 border-white shadow-sm relative">
            <div class="absolute inset-0 {{ $pulseColor }} rounded-full animate-ping opacity-25"></div>
            @if($isSuccess)
                <svg class="w-10 h-10 {{ $iconColor }} relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            @else
                <svg class="w-10 h-10 {{ $iconColor }} relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            @endif
        </div>
        
        <!-- Text Content -->
        <h3 class="text-2xl font-extrabold text-gray-900 mb-2 font-sans tracking-tight">
            {{ $titleText }}
        </h3>
        <p class="text-gray-600 mb-8 leading-relaxed font-medium">
            {{ $message }}
        </p>
        
        <!-- Action -->
        <button type="button" @click="show = false" class="w-full py-3 {{ $btnColor }} font-bold rounded-xl border hover:shadow-lg transition-all transform active:scale-95">
            {{ $btnText }}
        </button>
    </div>
</div>
