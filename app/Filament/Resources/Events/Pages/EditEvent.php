<?php

namespace App\Filament\Resources\Events\Pages;

use App\Filament\Resources\Events\EventResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    // Return to the events list after saving (don't stay on the edit screen).
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Attendance & certificate issuing live on the View page now, so the Edit
     * screen is event-details only — no relation managers render here.
     */
    protected function getAllRelationManagers(): array
    {
        return [];
    }
}
