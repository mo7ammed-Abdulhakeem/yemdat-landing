<x-filament-panels::page>
    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-sm dark:border-gray-700 dark:bg-gray-800/50">
        <p class="font-semibold text-gray-700 dark:text-gray-200">Available tokens</p>
        <p class="mt-1 text-gray-500 dark:text-gray-400">Paste these into the HTML; they are replaced when a certificate is generated:</p>
        <div class="mt-2 flex flex-wrap gap-2 font-mono text-xs">
            @foreach (['{member_name}', '{event_title_en}', '{event_title_ar}', '{date}', '{issued_year}', '{serial}', '{verify_url}', '{qr}', '{background_url}'] as $token)
                <span class="rounded bg-white px-2 py-1 text-primary-600 ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-gray-700">{{ $token }}</span>
            @endforeach
        </div>
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
