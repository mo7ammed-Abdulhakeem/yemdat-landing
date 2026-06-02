<?php

namespace App\Filament\Resources\TrainerRequests\Pages;

use App\Filament\Resources\TrainerRequests\TrainerRequestResource;
use App\Models\TrainerRequest;
use App\Support\CsvExport;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListTrainerRequests extends ListRecords
{
    protected static string $resource = TrainerRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Export CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(fn () => CsvExport::download(
                    'trainer-requests-'.now()->format('Y-m-d').'.csv',
                    ['Name', 'Email', 'Phone', 'Country', 'LinkedIn', 'Program type', 'Duration (hours)', 'Duration (days)', 'Agenda', 'Agreed to free provision', 'Submitted'],
                    TrainerRequest::query()->orderBy('created_at')->lazy()->map(fn (TrainerRequest $t) => [
                        $t->name,
                        $t->email,
                        $t->phone_number,
                        $t->country,
                        $t->linkedin_url,
                        $t->program_type,
                        $t->duration_hours,
                        $t->duration_days,
                        $t->agenda,
                        $t->agreed_to_free_provision,
                        $t->created_at,
                    ]),
                )),
        ];
    }
}
