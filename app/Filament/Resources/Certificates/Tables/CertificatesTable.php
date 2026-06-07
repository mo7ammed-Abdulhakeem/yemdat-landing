<?php

namespace App\Filament\Resources\Certificates\Tables;

use App\Actions\Certificates\SendCertificateEmail;
use App\Filament\Resources\Certificates\CertificateResource;
use App\Models\Certificate;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CertificatesTable
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
                TextColumn::make('issuer.name')
                    ->label('Issued by')
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
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('email')
                        ->label('Email to members')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->modalDescription('Emails each selected, non-revoked certificate to its member.')
                        ->action(function ($records): void {
                            $sent = 0;
                            $skipped = 0;

                            foreach ($records as $certificate) {
                                if ($certificate->revoked_at !== null) {
                                    $skipped++;
                                    continue;
                                }
                                try {
                                    app(SendCertificateEmail::class)->execute($certificate);
                                    $sent++;
                                } catch (\Throwable $e) {
                                    $skipped++;
                                }
                            }

                            Notification::make()
                                ->title("Emailed {$sent} certificate(s)".($skipped ? ", skipped {$skipped}." : '.'))
                                ->{$sent > 0 ? 'success' : 'warning'}()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
