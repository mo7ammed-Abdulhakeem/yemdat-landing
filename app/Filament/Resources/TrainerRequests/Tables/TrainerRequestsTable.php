<?php

namespace App\Filament\Resources\TrainerRequests\Tables;

use App\Filament\Resources\TrainerRequests\TrainerRequestResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TrainerRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('phone_number')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('program_type')
                    ->searchable(),
                TextColumn::make('duration_hours')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('duration_days')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('agreed_to_free_provision')
                    ->boolean(),
                TextColumn::make('linkedin_url')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
