<x-app-layout>
    @php
        $ar = app()->getLocale() === 'ar';
        $levelLabels = [
            'beginner' => $ar ? 'مبتدئ' : 'Beginner',
            'intermediate' => $ar ? 'متوسط' : 'Intermediate',
            'advanced' => $ar ? 'متقدم' : 'Advanced',
        ];
        $stepNumber = 0;
    @endphp

    <div class="bg-surface min-h-screen py-12" dir="{{ $ar ? 'rtl' : 'ltr' }}">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="flex mb-6 text-sm" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="text-ink-soft hover:text-primary">{{ $ar ? 'الرئيسية' : 'Home' }}</a>
                <span class="mx-2 text-ink-soft">/</span>
                <a href="{{ route('paths.index') }}" class="text-ink-soft hover:text-primary">{{ $ar ? 'مسارات التعلّم' : 'Learning Paths' }}</a>
            </nav>

            {{-- Header --}}
            <div class="bg-surface-raised rounded-card shadow-card border border-border overflow-hidden mb-10">
                @if ($path->image)
                    <img src="{{ asset('storage/'.$path->image) }}" alt="{{ $path->title }}" loading="lazy" class="w-full h-56 object-cover">
                @endif
                <div class="p-8">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        @if ($path->level)
                            <x-ui.badge variant="accent">{{ $levelLabels[$path->level] ?? $path->level }}</x-ui.badge>
                        @endif
                        @if ($path->specialtyOption)
                            <x-ui.badge variant="neutral">{{ $path->specialtyOption->name }}</x-ui.badge>
                        @endif
                        @if ($path->estimated_weeks)
                            <x-ui.badge variant="info">{{ $path->estimated_weeks }} {{ $ar ? 'أسبوع' : 'weeks' }}</x-ui.badge>
                        @endif
                        <x-ui.badge variant="neutral">{{ $path->steps->count() }} {{ $ar ? 'خطوة' : 'steps' }}</x-ui.badge>
                    </div>

                    <h1 class="text-3xl font-extrabold text-ink mb-4">{{ $path->title }}</h1>

                    @if ($path->description)
                        <p class="text-ink-soft leading-relaxed whitespace-pre-line">{{ $path->description }}</p>
                    @endif
                </div>
            </div>

            {{-- Steps roadmap --}}
            <h2 class="text-xl font-bold text-ink mb-6">{{ $ar ? 'خطوات المسار' : 'The roadmap' }}</h2>

            @if ($path->steps->count() > 0)
                @foreach ($groupedSteps as $phase => $steps)
                    @if ($phase !== '')
                        <h3 class="text-sm font-bold uppercase tracking-wider text-accent-hover mt-8 mb-4">{{ $phase }}</h3>
                    @endif
                    @foreach ($steps as $step)
                        <x-paths.step :step="$step" :number="++$stepNumber" />
                    @endforeach
                @endforeach
            @else
                <x-ui.card>
                    <p class="text-ink-soft text-center">{{ $ar ? 'لم تُضف خطوات بعد.' : 'No steps added yet.' }}</p>
                </x-ui.card>
            @endif

            {{-- Footer CTA --}}
            <div class="mt-10 text-center">
                <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-btn bg-primary text-white font-semibold hover:bg-primary-hover transition shadow-sm">
                    {{ $ar ? 'تصفّح فعاليات يمدات' : 'Browse Yemdat events' }}
                    <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
