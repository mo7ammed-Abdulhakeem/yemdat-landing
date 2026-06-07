<x-app-layout>
    @php $ar = app()->getLocale() === 'ar'; @endphp

    <div class="py-12 bg-surface" dir="{{ $ar ? 'rtl' : 'ltr' }}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Hero --}}
            <div class="text-center mb-12 max-w-3xl mx-auto">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-accent/20 text-primary text-sm font-semibold mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25M3 6.7v12.48c0 .84.88 1.38 1.63 1.01l3.87-1.94c.31-.16.69-.16 1 0l5 2.5c.31.16.69.16 1 0l3.87-1.94c.38-.19.63-.58.63-1V4.82c0-.84-.88-1.38-1.63-1.01l-3.87 1.94c-.31.16-.69.16-1 0l-5-2.5a1.12 1.12 0 0 0-1 0L3.63 5.19C3.25 5.38 3 5.77 3 6.7Z"/></svg>
                    {{ $ar ? 'مسارات التعلّم' : 'Learning Paths' }}
                </span>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-ink mb-4">
                    {{ $ar ? 'مسارك الموجّه نحو مهنة في البيانات' : 'Your guided journey into a data career' }}
                </h1>
                <p class="text-lg text-ink-soft">
                    {{ $ar
                        ? 'مسارات منظّمة خطوة بخطوة — تجمع فعاليات يمدات ومصادر مختارة — لمساعدتك على البدء أو تغيير مسارك المهني بثقة.'
                        : 'Step-by-step roadmaps — combining Yemdat events with hand-picked resources — to help you start or switch into a data career with confidence.' }}
                </p>
            </div>

            {{-- Grid --}}
            @if ($paths->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($paths as $path)
                        <x-paths.path-card :path="$path" />
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <x-ui.card class="max-w-md mx-auto">
                        <p class="text-ink-soft">
                            {{ $ar ? 'لا توجد مسارات منشورة حتى الآن. تابعنا قريبًا!' : 'No learning paths published yet. Check back soon!' }}
                        </p>
                        <a href="{{ route('events.index') }}" class="mt-4 inline-block text-primary font-semibold hover:underline">
                            {{ $ar ? 'تصفّح الفعاليات' : 'Browse events' }} &rarr;
                        </a>
                    </x-ui.card>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
