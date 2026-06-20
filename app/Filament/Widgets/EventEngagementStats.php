<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithAnalytics;
use App\Models\Event;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;

/**
 * Show/completion rates over PAST events only — upcoming events can't be
 * judged on attendance yet. The date range filters by event start date.
 */
class EventEngagementStats extends StatsOverviewWidget
{
    use InteractsWithAnalytics;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $d = $this->analyticsCache('event-engagement', function (): array {
            [$start, $end] = $this->analyticsRange();

            $agg = DB::table('event_member')
                ->join('events', 'events.id', '=', 'event_member.event_id')
                ->whereRaw('COALESCE(events.end_date, events.start_date) < ?', [now()])
                ->when($start, fn ($q) => $q->whereBetween('events.start_date', [$start, $end]))
                ->selectRaw('COUNT(*) as registered, COUNT(event_member.attended_at) as attended, COUNT(event_member.completed_at) as completed')
                ->first();

            $pastEvents = Event::query()
                ->whereRaw('COALESCE(end_date, start_date) < ?', [now()])
                ->when($start, fn ($q) => $q->whereBetween('start_date', [$start, $end]))
                ->count();

            return [
                'registered' => (int) $agg->registered,
                'attended' => (int) $agg->attended,
                'completed' => (int) $agg->completed,
                'past_events' => $pastEvents,
            ];
        });

        $showRate = $d['registered'] > 0 ? (int) round(($d['attended'] / $d['registered']) * 100) : 0;
        $completionRate = $d['attended'] > 0 ? (int) round(($d['completed'] / $d['attended']) * 100) : 0;
        $avgRegistrations = $d['past_events'] > 0 ? (int) round($d['registered'] / $d['past_events']) : 0;

        return [
            Stat::make(__('analytics.stats.past_events'), Number::format($d['past_events']))
                ->description($this->analyticsRangeLabel())
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
            Stat::make(__('analytics.stats.avg_registrations'), Number::format($avgRegistrations))
                ->description(__('analytics.stats.across_past_events', ['count' => $d['past_events']]))
                ->descriptionIcon('heroicon-m-ticket')
                ->color('primary'),
            Stat::make(__('analytics.stats.show_rate'), $showRate.'%')
                ->description(__('analytics.stats.attended_of_registered', [
                    'attended' => Number::format($d['attended']),
                    'registered' => Number::format($d['registered']),
                ]))
                ->descriptionIcon('heroicon-m-user-group')
                ->color($showRate >= 50 ? 'success' : 'warning'),
            Stat::make(__('analytics.stats.completion_rate'), $completionRate.'%')
                ->description(__('analytics.stats.completed_of_attended', [
                    'completed' => Number::format($d['completed']),
                    'attended' => Number::format($d['attended']),
                ]))
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color($completionRate >= 50 ? 'success' : 'warning'),
        ];
    }
}
