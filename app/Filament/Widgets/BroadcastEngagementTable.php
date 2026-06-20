<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\EmailBroadcasts\EmailBroadcastResource;
use App\Filament\Widgets\Concerns\InteractsWithAnalytics;
use App\Models\EmailBroadcast;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Str;

/**
 * Per-campaign engagement. Aggregate aliases deliberately avoid the model's
 * opens_count / unsubscribes_count accessors, which run a query per row.
 */
class BroadcastEngagementTable extends TableWidget
{
    use InteractsWithAnalytics;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('analytics.tables.broadcasts.heading'))
            ->query(
                EmailBroadcast::query()
                    ->where('status', 'sent')
                    ->withCount([
                        'recipients as recipients_opened' => fn ($q) => $q->whereNotNull('opened_at'),
                        'recipients as recipients_unsubscribed' => fn ($q) => $q->whereNotNull('unsubscribed_at'),
                    ])
                    ->latest('sent_at')
            )
            ->columns([
                TextColumn::make('subject')
                    ->label(__('analytics.tables.broadcasts.columns.subject'))
                    ->state(fn (EmailBroadcast $record): string => Str::limit(
                        (app()->getLocale() === 'ar' ? $record->subject_ar : $record->subject_en)
                            ?: ($record->subject_en ?? $record->subject_ar ?? '—'),
                        45,
                    ))
                    ->wrap(),
                TextColumn::make('audience_label')
                    ->label(__('analytics.tables.broadcasts.columns.audience'))
                    ->badge()
                    ->color('gray'),
                TextColumn::make('sent_at')
                    ->label(__('analytics.tables.broadcasts.columns.sent_at'))
                    ->formatStateUsing(fn ($state): string => $state?->translatedFormat('d M Y') ?? '—')
                    ->sortable(),
                TextColumn::make('total_recipients')
                    ->label(__('analytics.tables.broadcasts.columns.recipients'))
                    ->numeric(),
                TextColumn::make('recipients_opened')
                    ->label(__('analytics.tables.broadcasts.columns.opened'))
                    ->numeric(),
                TextColumn::make('open_rate_display')
                    ->label(__('analytics.tables.broadcasts.columns.open_rate'))
                    ->state(fn (EmailBroadcast $record): string => $record->total_recipients > 0
                        ? round(($record->recipients_opened / $record->total_recipients) * 100).'%'
                        : '—')
                    ->badge()
                    ->color(function (EmailBroadcast $record): string {
                        if ($record->total_recipients <= 0) {
                            return 'gray';
                        }
                        $rate = ($record->recipients_opened / $record->total_recipients) * 100;

                        return $rate >= 40 ? 'success' : ($rate >= 20 ? 'warning' : 'danger');
                    }),
                TextColumn::make('recipients_unsubscribed')
                    ->label(__('analytics.tables.broadcasts.columns.unsubscribed'))
                    ->numeric()
                    ->color(fn (int $state): string => $state > 0 ? 'danger' : 'gray'),
            ])
            ->recordUrl(fn (EmailBroadcast $record): string => EmailBroadcastResource::getUrl('view', ['record' => $record]))
            ->paginated([5, 10])
            ->defaultPaginationPageOption(5)
            ->emptyStateHeading(__('analytics.tables.broadcasts.empty'));
    }
}
