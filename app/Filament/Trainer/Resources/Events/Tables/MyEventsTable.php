<?php

namespace App\Filament\Trainer\Resources\Events\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MyEventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('start_date', 'desc')
            ->modifyQueryUsing(fn ($query) => $query->withCount('members'))
            ->columns([
                TextColumn::make('title_en')
                    ->label('Title')
                    ->description(fn ($record) => $record->title_ar)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('format')
                    ->badge()
                    ->sortable(),
                TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('members_count')
                    ->label('Registrations')
                    ->counts('members')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('format')
                    ->options([
                        'event' => 'Event',
                        'workshop' => 'Workshop',
                        'course' => 'Course',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
