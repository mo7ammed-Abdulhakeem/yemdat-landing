<?php

namespace App\Filament\Trainer\Resources\Certificates\Pages;

use App\Filament\Resources\Certificates\CertificateResource;
use App\Filament\Trainer\Resources\Certificates\TrainerCertificateResource;
use Filament\Resources\Pages\ViewRecord;

class ViewMyCertificate extends ViewRecord
{
    protected static string $resource = TrainerCertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CertificateResource::downloadAction(),
            CertificateResource::emailAction(),
            CertificateResource::revokeAction(),
            CertificateResource::reinstateAction(),
        ];
    }
}
