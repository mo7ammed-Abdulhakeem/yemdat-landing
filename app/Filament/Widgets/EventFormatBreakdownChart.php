<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithAnalytics;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\DB;

/**
 * Where registration demand goes: by event format (event/workshop/course)
 * or difficulty level — toggled with the widget's own filter select.
 */
class EventFormatBreakdownChart extends ChartWidget
{
    use InteractsWithAnalytics;

    protected int|string|array $columnSpan = ['default' => 'full', 'md' => 1, 'xl' => 2];

    protected ?string $maxHeight = '280px';

    public ?string $filter = 'format';

    public function getHeading(): string|Htmlable|null
    {
        return __('analytics.charts.format_breakdown.heading');
    }

    public function getDescription(): string|Htmlable|null
    {
        return $this->analyticsRangeLabel();
    }

    protected function getFilters(): ?array
    {
        return [
            'format' => __('analytics.charts.format_breakdown.by_format'),
            'level' => __('analytics.charts.format_breakdown.by_level'),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $dimension = $this->filter === 'level' ? 'level' : 'format';

        return $this->analyticsCache("format-breakdown.$dimension", function () use ($dimension): array {
            [$start, $end] = $this->analyticsRange();

            $rows = DB::table('event_member')
                ->join('events', 'events.id', '=', 'event_member.event_id')
                ->when($start, fn ($q) => $q->whereBetween('event_member.created_at', [$start, $end]))
                ->selectRaw("COALESCE(events.$dimension, 'unspecified') as k, COUNT(*) as c")
                ->groupBy('k')
                ->orderByDesc('c')
                ->pluck('c', 'k');

            $labelKey = $dimension === 'level' ? 'levels' : 'formats';
            $labels = $rows->keys()
                ->map(function (string $k) use ($labelKey): string {
                    if ($k === 'unspecified') {
                        return __('analytics.common.unspecified');
                    }
                    $translated = __("analytics.charts.format_breakdown.$labelKey.$k");

                    return str_contains($translated, 'analytics.charts') ? $k : $translated;
                })
                ->all();

            return [
                'datasets' => [[
                    'data' => $rows->values()->all(),
                    'backgroundColor' => ['#593E2D', '#F2CB57', '#C88D16', '#6B5847'],
                ]],
                'labels' => $labels,
            ];
        });
    }

    protected function getOptions(): array
    {
        return $this->analyticsBaseOptions([
            'plugins' => ['legend' => ['position' => 'bottom']],
        ]);
    }
}
