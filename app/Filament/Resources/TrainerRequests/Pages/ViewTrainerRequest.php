<?php

namespace App\Filament\Resources\TrainerRequests\Pages;

use App\Filament\Concerns\HasRecordNavigation;
use App\Filament\Resources\TrainerRequests\TrainerRequestResource;
use App\Filament\Support\ReplyAction;
use App\Models\TrainerRequest;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTrainerRequest extends ViewRecord
{
    use HasRecordNavigation;

    protected static string $resource = TrainerRequestResource::class;

    protected function navPage(): string
    {
        return 'view';
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->previousRecordAction(),
            $this->nextRecordAction(),
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
        ];
    }
}
