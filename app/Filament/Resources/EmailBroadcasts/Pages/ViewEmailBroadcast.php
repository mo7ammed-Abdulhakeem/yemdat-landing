<?php

namespace App\Filament\Resources\EmailBroadcasts\Pages;

use App\Filament\Resources\EmailBroadcasts\EmailBroadcastResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEmailBroadcast extends ViewRecord
{
    protected static string $resource = EmailBroadcastResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->visible(fn ($record) => $record->status === 'draft'),
            DeleteAction::make()->before(fn ($record) => $record->recipients()->delete()),
        ];
    }
}
