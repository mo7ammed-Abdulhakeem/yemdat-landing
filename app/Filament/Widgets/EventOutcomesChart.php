<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithAnalytics;
use App\Models\Event;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Registered vs attended vs completed for the most recent past events —
 * shows which events actually convert registrants into completers.
 */
class EventOutcomesChart extends ChartWidget
{
    use InteractsWithAnalytics;

    protected int|string|array $columnSpan = ['default' => 'full', 'xl' => 2];

    protected ?string $maxHeight = '320px';

    public function getHeading(): string|Htmlable|null
    {
        return __('analytics.charts.event_outcomes.heading');
    }

    public function getDescription(): string|Htmlable|null
    {
        return $this->analyticsRangeLabel();
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        return $this->analyticsCache('event-outcomes', function (): array {
            [$start, $end] = $this->analyticsRange();

            $events = Event::query()
                ->whereRaw('COALESCE(end_date, start_date) < ?', [now()])
                ->when($start, fn ($q) => $q->whereBetween('start_date', [$start, $end]))
                ->orderByDesc('start_date')
                ->limit(8)
                ->get();

            $stats = DB::table('event_member')
                ->whereIn('event_id', $events->pluck('id'))
                ->selectRaw('event_id, COUNT(*) as registered, COUNT(attended_at) as attended, COUNT(completed_at) as completed')
                ->groupBy('event_id')
                ->get()
                ->keyBy('event_id');

            $row = fn (string $key): array => $events
                ->map(fn (Event $e): int => (int) ($stats[$e->id]->{$key} ?? 0))
                ->all();

            return [
                'datasets' => [
                    [
                        'label' => __('analytics.charts.event_outcomes.registered'),
                        'data' => $row('registered'),
                        'backgroundColor' => '#F2CB57',
                    ],
                    [
                        'label' => __('analytics.charts.event_outcomes.attended'),
                        'data' => $row('attended'),
                        'backgroundColor' => '#C88D16',
                    ],
                    [
                        'label' => __('analytics.charts.event_outcomes.completed'),
                        'data' => $row('completed'),
                        'backgroundColor' => '#2F855A',
                    ],
                ],
                'labels' => $events->map(fn (Event $e): string => Str::limit($e->title, 25))->all(),
            ];
        });
    }

    protected function getOptions(): array
    {
        return $this->analyticsBaseOptions([
            'indexAxis' => 'y',
            'plugins' => ['legend' => ['position' => 'bottom']],
        ]);
    }
}
