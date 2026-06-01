<?php

namespace App\Filament\Resources\Members\RelationManagers;

use App\Filament\Resources\Contacts\ContactResource;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SentMessagesRelationManager extends RelationManager
{
    protected static string $relationship = 'contacts';

    protected static ?string $title = 'Sent messages';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('subject')
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('subject')
                    ->label('Subject')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('message')
                    ->label('Message')
                    ->limit(60)
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Sent')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('viewMessage')
                    ->label('Read message')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn ($record) => ContactResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
