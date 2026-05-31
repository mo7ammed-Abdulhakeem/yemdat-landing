<?php

namespace App\Filament\Resources\TrainerRequests\Pages;

use App\Filament\Resources\TrainerRequests\TrainerRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTrainerRequest extends EditRecord
{
    protected static string $resource = TrainerRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
