<?php

namespace App\Filament\Resources\Members\Tables;

use App\Filament\Resources\Members\MemberResource;
use App\Models\MembershipTier;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class MembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('full_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('membership_type')
                    ->label('Membership')
                    ->badge()
                    ->sortable(),
                TextColumn::make('country')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('specialty')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('phone_number')
                    ->label('Phone')
                    ->formatStateUsing(fn ($state, $record) => trim(($record->phone_code ? $record->phone_code . ' ' : '') . $state))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('email_verified_at')
                    ->label('Verified')
                    ->dateTime()
                    ->placeholder('Not verified')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('unsubscribed_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('membership_type')
                    ->label('Membership')
                    ->options(fn () => MembershipTier::orderBy('sort_order')->pluck('name_en', 'slug')),
                SelectFilter::make('gender')
                    ->options(['Male' => 'Male', 'Female' => 'Female']),
                TernaryFilter::make('email_verified_at')
                    ->label('Email verified')
                    ->nullable(),
                TernaryFilter::make('unsubscribed_at')
                    ->label('Unsubscribed')
                    ->placeholder('All')
                    ->trueLabel('Unsubscribed only')
                    ->falseLabel('Subscribed only')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('unsubscribed_at'),
                        false: fn ($query) => $query->whereNull('unsubscribed_at'),
                        blank: fn ($query) => $query,
                    ),
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
