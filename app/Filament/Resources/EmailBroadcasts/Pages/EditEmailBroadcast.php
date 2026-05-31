<?php

namespace App\Filament\Resources\EmailBroadcasts\Pages;

use App\Filament\Resources\EmailBroadcasts\EmailBroadcastResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEmailBroadcast extends EditRecord
{
    protected static string $resource = EmailBroadcastResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
