<?php

namespace App\Filament\Trainer\Resources\Events\Pages;

use App\Filament\Trainer\Resources\Events\TrainerEventResource;
use Filament\Resources\Pages\ListRecords;

class ListMyEvents extends ListRecords
{
    protected static string $resource = TrainerEventResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
