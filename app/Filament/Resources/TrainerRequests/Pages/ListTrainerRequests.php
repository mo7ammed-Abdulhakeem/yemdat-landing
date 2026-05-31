<?php

namespace App\Filament\Resources\TrainerRequests\Pages;

use App\Filament\Resources\TrainerRequests\TrainerRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTrainerRequests extends ListRecords
{
    protected static string $resource = TrainerRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
