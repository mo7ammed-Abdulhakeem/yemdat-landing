<?php

namespace App\Filament\Trainer\Resources\Certificates\Tables;

use App\Filament\Resources\Certificates\CertificateResource;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class MyCertificatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('issued_at', 'desc')
            ->columns([
                TextColumn::make('serial')
                    ->label('Serial')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),
                TextColumn::make('member.full_name')
                    ->label('Member')
                    ->searchable(),
                TextColumn::make('event.title_en')
                    ->label('Event')
                    ->limit(40)
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(fn ($record) => $record->revoked_at ? 'Revoked' : 'Valid')
                    ->color(fn ($state) => $state === 'Valid' ? 'success' : 'danger'),
                TextColumn::make('issued_at')
                    ->label('Issued')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('emailed_at')
                    ->label('Emailed')
                    ->dateTime()
                    ->placeholder('Not sent')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                TernaryFilter::make('revoked_at')
                    ->label('Status')
                    ->placeholder('All')
                    ->trueLabel('Revoked only')
                    ->falseLabel('Valid only')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('revoked_at'),
                        false: fn ($query) => $query->whereNull('revoked_at'),
                        blank: fn ($query) => $query,
                    ),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    CertificateResource::downloadAction(),
                    CertificateResource::emailAction(),
                    CertificateResource::revokeAction(),
                    CertificateResource::reinstateAction(),
                ]),
            ]);
    }
}
