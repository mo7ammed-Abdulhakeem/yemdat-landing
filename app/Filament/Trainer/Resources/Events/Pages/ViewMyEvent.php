<?php

namespace App\Filament\Trainer\Resources\Events\Pages;

use App\Filament\Trainer\Resources\Events\TrainerEventResource;
use Filament\Resources\Pages\ViewRecord;

class ViewMyEvent extends ViewRecord
{
    protected static string $resource = TrainerEventResource::class;

    // Read-only: no edit/delete header actions. The Attendees relation manager
    // (registrations + certificate issuing) renders below the infolist.
    protected function getHeaderActions(): array
    {
        return [];
    }
}
