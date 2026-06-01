<?php

namespace App\Filament\Resources\TrainerRequests\Tables;

use App\Filament\Resources\TrainerRequests\TrainerRequestResource;
use App\Models\TrainerRequest;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class TrainerRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('phone_number')
                    ->label('Phone')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('program_type')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('duration_hours')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('duration_days')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                IconColumn::make('agreed_to_free_provision')
                    ->label('Free provision')
                    ->boolean(),
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
                SelectFilter::make('program_type')
                    ->options(fn () => TrainerRequest::query()
                        ->whereNotNull('program_type')
                        ->distinct()
                        ->orderBy('program_type')
                        ->pluck('program_type', 'program_type')
                        ->all()),
                TernaryFilter::make('agreed_to_free_provision')
                    ->label('Agreed to free provision'),
            ])
            ->recordUrl(fn ($record): string => TrainerRequestResource::getUrl('view', ['record' => $record]))
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
