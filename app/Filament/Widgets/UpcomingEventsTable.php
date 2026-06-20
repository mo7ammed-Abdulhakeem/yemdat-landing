<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Events\EventResource;
use App\Filament\Widgets\Concerns\InteractsWithAnalytics;
use App\Models\Event;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Str;

/**
 * Operational readiness: which imminent events are filling up and which
 * are under-registered while there is still time to promote them.
 */
class UpcomingEventsTable extends TableWidget
{
    use InteractsWithAnalytics;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('analytics.tables.upcoming_events.heading'))
            ->query(
                Event::query()
                    ->where('is_active', true)
                    ->where('start_date', '>=', now())
                    ->withCount('members')
                    ->orderBy('start_date')
            )
            ->columns([
                TextColumn::make('title')
                    ->label(__('analytics.tables.upcoming_events.columns.title'))
                    ->state(fn (Event $record): string => Str::limit($record->title, 45))
                    ->wrap(),
                TextColumn::make('format')
                    ->label(__('analytics.tables.upcoming_events.columns.format'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => __('analytics.charts.format_breakdown.formats.'.$state))
                    ->color(fn (string $state): string => match ($state) {
                        'workshop' => 'warning',
                        'course' => 'success',
                        default => 'info',
                    })
                    ->placeholder('—'),
                TextColumn::make('level')
                    ->label(__('analytics.tables.upcoming_events.columns.level'))
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn (string $state): string => __('analytics.charts.format_breakdown.levels.'.$state))
                    ->placeholder('—'),
                TextColumn::make('start_date')
                    ->label(__('analytics.tables.upcoming_events.columns.starts'))
                    ->formatStateUsing(fn ($state): string => $state->translatedFormat('d M Y · H:i'))
                    ->sortable(),
                TextColumn::make('members_count')
                    ->label(__('analytics.tables.upcoming_events.columns.registrations'))
                    ->badge()
                    ->color(fn (int $state): string => $state >= 50 ? 'success' : ($state >= 10 ? 'warning' : 'danger')),
                TextColumn::make('trainer.name')
                    ->label(__('analytics.tables.upcoming_events.columns.trainer'))
                    ->placeholder('—'),
            ])
            ->recordUrl(fn (Event $record): string => EventResource::getUrl('view', ['record' => $record]))
            ->paginated([5, 10])
            ->defaultPaginationPageOption(5)
            ->emptyStateHeading(__('analytics.tables.upcoming_events.empty'));
    }
}
