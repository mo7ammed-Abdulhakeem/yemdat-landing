<?php

namespace App\Filament\Resources\TrainerRequests\Tables;

use App\Filament\Resources\TrainerRequests\TrainerRequestResource;
use App\Filament\Support\ReplyAction;
use App\Models\TrainerRequest;
use App\Support\CsvExport;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
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
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => TrainerRequest::statusOptions()[$state] ?? ucfirst((string) $state))
                    ->color(fn ($record) => $record->statusColor())
                    ->sortable(),
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
                SelectFilter::make('status')
                    ->options(TrainerRequest::statusOptions()),
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
                ActionGroup::make([
                    ViewAction::make(),
                    ReplyAction::make(fn (TrainerRequest $record): string => 'Re: Your trainer application — Yemdat'),
                    Action::make('markClosed')
                        ->label('Mark as closed')
                        ->icon('heroicon-o-check-circle')
                        ->color('gray')
                        ->visible(fn (TrainerRequest $record) => $record->status !== TrainerRequest::STATUS_CLOSED)
                        ->action(fn (TrainerRequest $record) => $record->update(['status' => TrainerRequest::STATUS_CLOSED])),
                    Action::make('reopen')
                        ->label('Reopen')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->visible(fn (TrainerRequest $record) => $record->status === TrainerRequest::STATUS_CLOSED)
                        ->action(fn (TrainerRequest $record) => $record->update(['status' => TrainerRequest::STATUS_NEW])),
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
                        ->action(fn ($records) => $records->each->update(['status' => TrainerRequest::STATUS_CLOSED]))
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('markNew')
                        ->label('Mark as new')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->action(fn ($records) => $records->each->update(['status' => TrainerRequest::STATUS_NEW]))
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
                'trainer-requests-'.now()->format('Y-m-d').'.csv',
                ['Name', 'Email', 'Phone', 'LinkedIn', 'Program type', 'Duration days', 'Duration hours', 'Agenda', 'Agreed to free', 'Status', 'Submitted'],
                $records->map(fn (TrainerRequest $t) => [
                    $t->name,
                    $t->email,
                    $t->phone_number,
                    $t->linkedin_url,
                    $t->program_type,
                    $t->duration_days,
                    $t->duration_hours,
                    strip_tags((string) $t->agenda),
                    $t->agreed_to_free_provision,
                    $t->statusLabel(),
                    $t->created_at,
                ]),
            ))
            ->deselectRecordsAfterCompletion();
    }
}
