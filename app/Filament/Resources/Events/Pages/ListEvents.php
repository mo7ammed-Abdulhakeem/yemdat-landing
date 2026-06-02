<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use App\Models\Event;
use App\Support\CsvExport;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportRegistrations')
                ->label('Export registrations')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(fn () => CsvExport::download(
                    'event-registrations-'.now()->format('Y-m-d').'.csv',
                    ['Event', 'Member', 'Email', 'Registered at', 'Attended at', 'Completed at'],
                    (function () {
                        foreach (Event::query()->with('members')->orderBy('start_date')->lazy() as $event) {
                            foreach ($event->members as $member) {
                                yield [
                                    $event->title_en,
                                    $member->full_name,
                                    $member->email,
                                    $member->pivot->created_at,
                                    $member->pivot->attended_at,
                                    $member->pivot->completed_at,
                                ];
                            }
                        }
                    })(),
                )),
            CreateAction::make(),
        ];
    }
}
