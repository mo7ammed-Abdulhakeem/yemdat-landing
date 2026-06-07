<?php

namespace App\Filament\Resources\Members\Pages;

use App\Filament\Concerns\HasRecordNavigation;
use App\Filament\Resources\Members\MemberResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMember extends EditRecord
{
    use HasRecordNavigation;

    protected static string $resource = MemberResource::class;

    protected function navPage(): string
    {
        return 'edit';
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->previousRecordAction(),
            $this->nextRecordAction(),
            DeleteAction::make(),
        ];
    }
}
