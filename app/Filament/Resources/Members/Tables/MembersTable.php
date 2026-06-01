<?php

namespace App\Filament\Resources\Members\Tables;

use App\Filament\Resources\Members\MemberResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('otp_code')
                    ->searchable(),
                TextColumn::make('otp_expires_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('gender')
                    ->searchable(),
                TextColumn::make('phone_code')
                    ->searchable(),
                TextColumn::make('phone_number')
                    ->searchable(),
                TextColumn::make('education_level')
                    ->searchable(),
                TextColumn::make('country')
                    ->searchable(),
                TextColumn::make('specialty')
                    ->searchable(),
                TextColumn::make('specialty_other')
                    ->searchable(),
                TextColumn::make('linkedin_url')
                    ->searchable(),
                TextColumn::make('membership_type')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('unsubscribed_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordUrl(fn ($record): string => MemberResource::getUrl('view', ['record' => $record]))
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
