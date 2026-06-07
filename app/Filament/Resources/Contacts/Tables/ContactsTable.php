<?php

namespace App\Filament\Resources\Contacts\Tables;

use App\Filament\Resources\Contacts\ContactResource;
use App\Filament\Support\ReplyAction;
use App\Models\Contact;
use App\Support\CsvExport;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ContactsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => Contact::statusOptions()[$state] ?? ucfirst((string) $state))
                    ->color(fn ($record) => $record->statusColor())
                    ->sortable(),
                TextColumn::make('member.full_name')
                    ->label('Member')
                    ->placeholder('Guest')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Sender name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('phone_number')
                    ->label('Phone')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('subject')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(Contact::statusOptions()),
                TernaryFilter::make('member_id')
                    ->label('Sender')
                    ->placeholder('All')
                    ->trueLabel('Registered members')
                    ->falseLabel('Guests')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('member_id'),
                        false: fn ($query) => $query->whereNull('member_id'),
                        blank: fn ($query) => $query,
                    ),
            ])
            ->recordUrl(fn ($record): string => ContactResource::getUrl('view', ['record' => $record]))
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
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
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    self::exportBulkAction(),
                    BulkAction::make('markClosed')
                        ->label('Mark as closed')
                        ->icon('heroicon-o-check-circle')
                        ->color('gray')
                        ->action(fn ($records) => $records->each->update(['status' => Contact::STATUS_CLOSED]))
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('markNew')
                        ->label('Mark as new')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->action(fn ($records) => $records->each->update(['status' => Contact::STATUS_NEW]))
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function exportBulkAction(): BulkAction
    {
        return BulkAction::make('export')
            ->label('Export selected')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('gray')
            ->action(fn ($records) => CsvExport::download(
                'contacts-'.now()->format('Y-m-d').'.csv',
                ['Sender (member)', 'Name', 'Email', 'Phone', 'Subject', 'Message', 'Status', 'Received'],
                $records->map(fn (Contact $c) => [
                    $c->member?->full_name,
                    $c->name,
                    $c->email,
                    $c->phone_number,
                    $c->subject,
                    $c->message,
                    $c->statusLabel(),
                    $c->created_at,
                ]),
            ))
            ->deselectRecordsAfterCompletion();
    }
}
