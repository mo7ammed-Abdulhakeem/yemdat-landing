<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Yemdat — Design System Preview</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=tajawal:400,500,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-surface text-ink">

@php
    // Each block is rendered once LTR (English) and once RTL (Arabic) to verify both directions.
    $directions = [
        ['dir' => 'ltr', 'font' => 'font-sans', 'label' => 'LTR / English'],
        ['dir' => 'rtl', 'font' => 'font-arabic', 'label' => 'RTL / العربية'],
    ];
@endphp

<div class="max-w-5xl mx-auto px-6 py-12 space-y-16">
    <header>
        <x-ui.badge variant="accent">Design System</x-ui.badge>
        <h1 class="mt-3 text-4xl font-bold text-primary">Yemdat UI Components</h1>
        <p class="mt-2 text-ink-soft">A token-driven, RTL-aware component library. See <code>DESIGN.md</code> for usage.</p>
    </header>

    {{-- Color tokens --}}
    <section>
        <h2 class="text-xl font-semibold text-primary mb-4">Brand &amp; semantic colors</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3">
            @foreach (['bg-primary text-white' => 'primary', 'bg-accent text-ink' => 'accent', 'bg-accent-hover text-white' => 'accent-hover', 'bg-surface-sunken text-ink' => 'sunken', 'bg-success text-white' => 'success', 'bg-danger text-white' => 'danger'] as $cls => $name)
                <div class="{{ $cls }} rounded-card h-20 flex items-end p-2 text-xs font-semibold shadow-card">{{ $name }}</div>
            @endforeach
        </div>
    </section>

    @foreach ($directions as $d)
        <section dir="{{ $d['dir'] }}" class="{{ $d['font'] }} space-y-8 border-t border-border pt-10">
            <h2 class="text-2xl font-bold text-primary">{{ $d['label'] }}</h2>

            {{-- Buttons --}}
            <div class="space-y-3">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-ink-soft">Buttons</h3>
                <div class="flex flex-wrap gap-3 items-center">
                    <x-ui.button variant="primary">Primary</x-ui.button>
                    <x-ui.button variant="accent">Accent</x-ui.button>
                    <x-ui.button variant="outline">Outline</x-ui.button>
                    <x-ui.button variant="ghost">Ghost</x-ui.button>
                    <x-ui.button variant="danger">Danger</x-ui.button>
                </div>
                <div class="flex flex-wrap gap-3 items-center">
                    <x-ui.button size="sm">Small</x-ui.button>
                    <x-ui.button size="md">Medium</x-ui.button>
                    <x-ui.button size="lg">Large</x-ui.button>
                    <x-ui.button href="#">As link</x-ui.button>
                </div>
            </div>

            {{-- Badges --}}
            <div class="space-y-3">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-ink-soft">Badges</h3>
                <div class="flex flex-wrap gap-2 items-center">
                    <x-ui.badge>Neutral</x-ui.badge>
                    <x-ui.badge variant="accent">Accent</x-ui.badge>
                    <x-ui.badge variant="success">Active</x-ui.badge>
                    <x-ui.badge variant="danger">Past</x-ui.badge>
                    <x-ui.badge variant="warning">Draft</x-ui.badge>
                    <x-ui.badge variant="info">Info</x-ui.badge>
                </div>
            </div>

            {{-- Alerts --}}
            <div class="space-y-3">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-ink-soft">Alerts</h3>
                <x-ui.alert variant="success" title="Success">Your message has been sent successfully.</x-ui.alert>
                <x-ui.alert variant="danger" title="Error">Something went wrong, please try again.</x-ui.alert>
                <x-ui.alert variant="info">A neutral, informational note.</x-ui.alert>
            </div>

            {{-- Card + form --}}
            <div class="grid md:grid-cols-2 gap-6">
                <x-ui.card>
                    <x-ui.page-header title="Card heading" subtitle="A raised surface with form controls." class="mb-4" />
                    <form class="space-y-4">
                        <x-ui.input name="full_name" label="Full name" :required="true" placeholder="Jane Doe" />
                        <x-ui.input name="email" type="email" label="Email" placeholder="jane@example.com" />
                        <x-ui.textarea name="message" label="Message" placeholder="Write something…" />
                        <div class="flex gap-3">
                            <x-ui.button type="button" variant="primary">Submit</x-ui.button>
                            <x-ui.button type="button" variant="ghost">Cancel</x-ui.button>
                        </div>
                    </form>
                </x-ui.card>

                <x-ui.card>
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-bold text-ink">Intro to Data Analysis</h3>
                            <p class="text-sm text-ink-soft mt-1">Sample event card content.</p>
                        </div>
                        <x-ui.badge variant="success">Upcoming</x-ui.badge>
                    </div>
                    <p class="mt-4 text-sm text-ink-soft leading-relaxed">
                        Cards use <code>--shadow-card</code>, <code>--radius-card</code> and the
                        <code>surface-raised</code> / <code>border</code> tokens.
                    </p>
                    <div class="mt-5">
                        <x-ui.button variant="accent" size="sm">Register</x-ui.button>
                    </div>
                </x-ui.card>
            </div>
        </section>
    @endforeach
</div>

</body>
</html>
