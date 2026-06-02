<?php

namespace App\Filament\Resources\Specialties\Pages;

use App\Filament\Resources\Specialties\SpecialtyResource;
use App\Models\Specialty;
use App\Support\CsvExport;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Str;

class ListSpecialties extends ListRecords
{
    protected static string $resource = SpecialtyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Export CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(fn () => CsvExport::download(
                    'specialties-'.now()->format('Y-m-d').'.csv',
                    ['slug', 'name_en', 'name_ar', 'sort_order', 'is_active'],
                    Specialty::query()->ordered()->lazy()->map(fn (Specialty $s) => [
                        $s->slug,
                        $s->name_en,
                        $s->name_ar,
                        $s->sort_order,
                        $s->is_active ? 1 : 0,
                    ]),
                )),
            Action::make('import')
                ->label('Import CSV')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('gray')
                ->modalHeading('Import specialties from CSV')
                ->modalSubmitActionLabel('Import')
                ->schema([
                    FileUpload::make('csv')
                        ->label('CSV file')
                        ->acceptedFileTypes(['text/csv', 'text/plain', 'application/vnd.ms-excel', 'application/csv'])
                        ->storeFiles(false)
                        ->required()
                        ->helperText('Columns: slug, name_en, name_ar, sort_order, is_active. Existing rows (matched by slug) are updated; new slugs are created. Tip: export first to get the exact format.'),
                ])
                ->action(fn (array $data) => $this->importSpecialties($data)),
            CreateAction::make(),
        ];
    }

    protected function importSpecialties(array $data): void
    {
        $file = $data['csv'] ?? null;
        if (is_array($file)) {
            $file = reset($file) ?: null;
        }

        if (! $file) {
            Notification::make()->title('No file uploaded.')->danger()->send();

            return;
        }

        $handle = fopen($file->getRealPath(), 'r');
        if ($handle === false) {
            Notification::make()->title('Could not read the uploaded file.')->danger()->send();

            return;
        }

        $header = fgetcsv($handle) ?: [];
        if (isset($header[0])) {
            $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $header[0]); // strip UTF-8 BOM
        }
        $header = array_map(fn ($h) => Str::of((string) $h)->trim()->lower()->replace(' ', '_')->value(), $header);

        $created = 0;
        $updated = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle)) !== false) {
            // Skip blank lines.
            if (count(array_filter($row, fn ($v) => trim((string) $v) !== '')) === 0) {
                continue;
            }

            $record = array_combine($header, array_pad(array_slice($row, 0, count($header)), count($header), null));

            $nameEn = trim((string) ($record['name_en'] ?? ''));
            $nameAr = trim((string) ($record['name_ar'] ?? ''));
            $slug = Str::slug(trim((string) ($record['slug'] ?? '')) ?: $nameEn);

            if ($slug === '' || ($nameEn === '' && $nameAr === '')) {
                $skipped++;

                continue;
            }

            $isActiveRaw = strtolower(trim((string) ($record['is_active'] ?? '')));
            $isActive = $isActiveRaw === '' || in_array($isActiveRaw, ['1', 'yes', 'true', 'active', 'y'], true);

            $specialty = Specialty::updateOrCreate(
                ['slug' => $slug],
                [
                    'name_en' => $nameEn ?: $nameAr,
                    'name_ar' => $nameAr ?: $nameEn,
                    'sort_order' => (int) ($record['sort_order'] ?? 0),
                    'is_active' => $isActive,
                ],
            );

            $specialty->wasRecentlyCreated ? $created++ : $updated++;
        }

        fclose($handle);

        Notification::make()
            ->title('Specialties imported')
            ->body("Created {$created}, updated {$updated}, skipped {$skipped}.")
            ->success()
            ->send();
    }
}
