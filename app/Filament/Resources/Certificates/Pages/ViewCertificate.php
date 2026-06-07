<?php

namespace App\Filament\Resources\Certificates\Pages;

use App\Filament\Concerns\HasRecordNavigation;
use App\Filament\Resources\Certificates\CertificateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCertificate extends ViewRecord
{
    use HasRecordNavigation;

    protected static string $resource = CertificateResource::class;

    protected function navPage(): string
    {
        return 'view';
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->previousRecordAction(),
            $this->nextRecordAction(),
            CertificateResource::downloadAction(),
            CertificateResource::emailAction(),
            CertificateResource::revokeAction(),
            CertificateResource::reinstateAction(),
            DeleteAction::make(),
        ];
    }
}
