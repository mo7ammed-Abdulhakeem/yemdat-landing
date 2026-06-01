<?php

namespace App\Filament\Resources\EmailBroadcasts\Tables;

use App\Actions\Broadcasts\SendBroadcast;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EmailBroadcastsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subject_en')
                    ->label('Subject')
                    ->searchable()
                    ->limit(40)
                    ->description(fn ($record) => $record->subject_ar),
                TextColumn::make('audience_type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'all_members' ? 'All Members' : 'Event'),
                TextColumn::make('language')->badge(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'sent' => 'success',
                        'sending' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('total_recipients')->label('Recipients')->numeric()->sortable(),
                TextColumn::make('sent_at')->dateTime()->sortable()->placeholder('—'),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'sending' => 'Sending',
                        'sent' => 'Sent',
                    ]),
                SelectFilter::make('audience_type')
                    ->label('Audience')
                    ->options([
                        'all_members' => 'All Members',
                        'event_members' => 'Event',
                    ]),
            ])
            ->recordActions([
                Action::make('send')
                    ->label('Send')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalDescription('This queues the broadcast to its full audience (sent in daily chunks).')
                    ->visible(fn ($record) => $record->status === 'draft')
                    ->action(function ($record) {
                        $count = app(SendBroadcast::class)->send($record);
                        Notification::make()
                            ->title($count > 0 ? "Broadcast queued for {$count} recipient(s)." : 'No recipients found.')
                            ->{$count > 0 ? 'success' : 'warning'}()
                            ->send();
                    }),
                Action::make('sendToNew')
                    ->label('Send to new')
                    ->icon('heroicon-o-user-plus')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'sent'
                        && $record->audience_type === 'event_members'
                        && $record->event)
                    ->action(function ($record) {
                        $count = app(SendBroadcast::class)->sendToNew($record);
                        Notification::make()
                            ->title($count > 0 ? "Sending to {$count} new registrant(s)." : 'No new registrants.')
                            ->{$count > 0 ? 'success' : 'warning'}()
                            ->send();
                    }),
                EditAction::make()->visible(fn ($record) => $record->status === 'draft'),
                DeleteAction::make()->before(fn ($record) => $record->recipients()->delete()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
