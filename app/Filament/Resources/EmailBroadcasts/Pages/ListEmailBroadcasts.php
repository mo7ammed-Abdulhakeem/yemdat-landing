<?php

namespace App\Filament\Resources\EmailBroadcasts\Pages;

use App\Filament\Resources\EmailBroadcasts\EmailBroadcastResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmailBroadcasts extends ListRecords
{
    protected static string $resource = EmailBroadcastResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
