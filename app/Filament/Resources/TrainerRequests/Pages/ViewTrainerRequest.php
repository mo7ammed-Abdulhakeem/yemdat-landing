<?php

namespace App\Filament\Resources\TrainerRequests\Pages;

use App\Filament\Resources\TrainerRequests\TrainerRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTrainerRequest extends ViewRecord
{
    protected static string $resource = TrainerRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
