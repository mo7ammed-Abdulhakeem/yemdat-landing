{{--
    Global flash-message toast. Renders session('success') / session('error') / session('status')
    as a dismissible, auto-hiding toast. Place once in the app layout; aria-live announces it.
--}}
@php
    $ar = app()->getLocale() === 'ar';
    $toasts = [];
    if (session('success')) { $toasts[] = ['type' => 'success', 'message' => session('success')]; }
    if (session('status'))  { $toasts[] = ['type' => 'success', 'message' => session('status')]; }
    if (session('error'))   { $toasts[] = ['type' => 'error',   'message' => session('error')]; }
@endphp

@if (count($toasts))
    <div
        class="fixed top-20 inset-x-0 z-[60] flex flex-col items-center gap-2 px-4 pointer-events-none sm:top-24 sm:inset-x-auto sm:end-6 sm:items-end"
        aria-live="polite"
        aria-atomic="true"
    >
        @foreach ($toasts as $toast)
            <div
                x-data="{ show: false }"
                x-init="$nextTick(() => show = true); setTimeout(() => show = false, 5000)"
                x-show="show"
                x-cloak
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-2 rtl:sm:-translate-x-2"
                x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                role="status"
                class="pointer-events-auto w-full sm:w-auto sm:min-w-[20rem] sm:max-w-md flex items-start gap-3 rounded-xl border bg-white px-4 py-3 shadow-pop {{ $toast['type'] === 'success' ? 'border-success/30' : 'border-danger/30' }}"
            >
                <span class="shrink-0 mt-0.5 {{ $toast['type'] === 'success' ? 'text-success' : 'text-danger' }}">
                    @if ($toast['type'] === 'success')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @endif
                </span>
                <p class="flex-grow text-sm font-medium text-ink">{{ $toast['message'] }}</p>
                <button
                    type="button"
                    @click="show = false"
                    aria-label="{{ $ar ? 'إغلاق' : 'Dismiss' }}"
                    class="shrink-0 -mr-1 rtl:-mr-0 rtl:-ml-1 rounded-md p-0.5 text-ink-soft hover:text-ink focus:outline-none focus-visible:ring-2 focus-visible:ring-accent transition"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endforeach
    </div>
@endif
