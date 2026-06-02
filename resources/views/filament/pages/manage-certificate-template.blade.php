<x-filament-panels::page>
    @php
        $tokens = [
            '{member_name}'    => "Recipient's full name.",
            '{event_title_en}' => 'Event title in English.',
            '{event_title_ar}' => 'Event title in Arabic.',
            '{date}'           => 'Completion date, e.g. June 2, 2026.',
            '{issued_year}'    => 'Year the certificate was issued, e.g. 2026.',
            '{serial}'         => 'Unique certificate serial number.',
            '{verify_url}'     => 'Public URL where the certificate can be verified.',
            '{qr}'             => 'QR code image that links to the verify URL.',
            '{background_url}' => 'File path of the uploaded background image.',
        ];
    @endphp

    <div
        x-data="{
            copied: null,
            copy(text) {
                navigator.clipboard.writeText(text);
                this.copied = text;
                setTimeout(() => { if (this.copied === text) this.copied = null }, 1500);
            }
        }"
        class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-sm dark:border-gray-700 dark:bg-gray-800/50"
    >
        <div class="flex flex-wrap items-center justify-between gap-2">
            <p class="font-semibold text-gray-700 dark:text-gray-200">
                Available tokens
                <span x-cloak x-show="copied" x-text="'· copied ' + copied" class="ms-1 font-normal text-primary-600 dark:text-primary-400"></span>
            </p>
            <button
                type="button"
                @click="copy(@js(implode(' ', array_keys($tokens))))"
                class="rounded-lg bg-primary-600 px-2.5 py-1 text-xs font-medium text-white transition hover:bg-primary-500"
            >
                Copy all tokens
            </button>
        </div>
        <p class="mt-1 text-gray-500 dark:text-gray-400">Click a token to copy it, then paste it into the HTML below. Each one is replaced with real data when a certificate is generated:</p>
        <dl class="mt-3 grid gap-x-6 gap-y-2 sm:grid-cols-2">
            @foreach ($tokens as $token => $description)
                <div class="flex items-start gap-2">
                    <button
                        type="button"
                        @click="copy('{{ $token }}')"
                        title="Click to copy"
                        class="shrink-0 rounded bg-white px-2 py-1 font-mono text-xs text-primary-600 ring-1 ring-gray-200 transition hover:ring-primary-400 dark:bg-gray-900 dark:text-primary-400 dark:ring-gray-700"
                    >{{ $token }}</button>
                    <span class="pt-1 text-xs text-gray-500 dark:text-gray-400">{{ $description }}</span>
                </div>
            @endforeach
        </dl>
    </div>

    <form wire:submit="save" class="mt-6 space-y-6">
        {{ $this->form }}

        <div class="flex justify-end">
            <x-filament::button type="submit">
                Save Template
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
