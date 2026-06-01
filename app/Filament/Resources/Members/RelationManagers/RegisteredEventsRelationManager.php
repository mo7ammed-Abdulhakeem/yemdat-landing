<?php

namespace App\Filament\Resources\Members\RelationManagers;

use App\Filament\Resources\Events\EventResource;
use App\Models\Certificate;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RegisteredEventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    protected static ?string $title = 'Registered events';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title_en')
            ->columns([
                TextColumn::make('title_en')
                    ->label('Event')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->label('Date')
                    ->dateTime('M j, Y')
                    ->sortable(),
                IconColumn::make('attended')
                    ->label('Attended')
                    ->boolean()
                    ->getStateUsing(fn ($record) => (bool) $record->pivot->attended_at),
                IconColumn::make('completed')
                    ->label('Completed')
                    ->boolean()
                    ->getStateUsing(fn ($record) => (bool) $record->pivot->completed_at),
                TextColumn::make('certificate')
                    ->label('Certificate')
                    ->badge()
                    ->color(fn ($state) => $state === '—' ? 'gray' : 'success')
                    ->getStateUsing(fn ($record) => optional($this->certificateFor($record))->serial ?? '—'),
            ])
            ->recordActions([
                Action::make('openEvent')
                    ->label('View event')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn ($record) => EventResource::getUrl('edit', ['record' => $record])),
            ]);
    }

    protected function certificateFor($record): ?Certificate
    {
        return Certificate::where('member_id', $this->getOwnerRecord()->getKey())
            ->where('event_id', $record->getKey())
            ->first();
    }
}
