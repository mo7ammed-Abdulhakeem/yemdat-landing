<?php

namespace App\Filament\Resources\Contacts\Pages;

use App\Filament\Resources\Contacts\ContactResource;
use App\Models\Contact;
use App\Support\CsvExport;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListContacts extends ListRecords
{
    protected static string $resource = ContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Export CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(fn () => CsvExport::download(
                    'contacts-'.now()->format('Y-m-d').'.csv',
                    ['Sender (member)', 'Name', 'Email', 'Phone', 'Subject', 'Message', 'Received'],
                    Contact::query()->with('member')->orderBy('created_at')->lazy()->map(fn (Contact $c) => [
                        $c->member?->full_name,
                        $c->name,
                        $c->email,
                        $c->phone_number,
                        $c->subject,
                        $c->message,
                        $c->created_at,
                    ]),
                )),
        ];
    }
}
