<?php

namespace App\Filament\Trainer\Resources\Certificates\Pages;

use App\Filament\Trainer\Resources\Certificates\TrainerCertificateResource;
use Filament\Resources\Pages\ListRecords;

class ListMyCertificates extends ListRecords
{
    protected static string $resource = TrainerCertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
