<?php

namespace App\Filament\Resources\Contacts\Pages;

use App\Filament\Concerns\HasRecordNavigation;
use App\Filament\Resources\Contacts\ContactResource;
use App\Filament\Support\ReplyAction;
use App\Models\Contact;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;

class ViewContact extends ViewRecord
{
    use HasRecordNavigation;

    protected static string $resource = ContactResource::class;

    protected function navPage(): string
    {
        return 'view';
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->previousRecordAction(),
            $this->nextRecordAction(),
            ReplyAction::make(fn (Contact $record): string => 'Re: '.$record->subject),
            Action::make('markClosed')
                ->label('Mark as closed')
                ->icon('heroicon-o-check-circle')
                ->color('gray')
                ->visible(fn (Contact $record) => $record->status !== Contact::STATUS_CLOSED)
                ->action(fn (Contact $record) => $record->update(['status' => Contact::STATUS_CLOSED])),
            Action::make('reopen')
                ->label('Reopen')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->visible(fn (Contact $record) => $record->status === Contact::STATUS_CLOSED)
                ->action(fn (Contact $record) => $record->update(['status' => Contact::STATUS_NEW])),
            DeleteAction::make(),
        ];
    }
}
