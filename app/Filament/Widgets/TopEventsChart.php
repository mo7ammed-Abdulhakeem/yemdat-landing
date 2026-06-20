<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithAnalytics;
use App\Models\Event;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;

class TopEventsChart extends ChartWidget
{
    use InteractsWithAnalytics;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '320px';

    public function getHeading(): string|Htmlable|null
    {
        return __('analytics.charts.top_events.heading');
    }

    public function getDescription(): string|Htmlable|null
    {
        return __('analytics.common.all_time');
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        return $this->analyticsCache('top-events', function (): array {
            $events = Event::withCount('members')
                ->orderByDesc('members_count')
                ->limit(10)
                ->get();

            return [
                'datasets' => [[
                    'label' => __('analytics.charts.registrations.dataset'),
                    'data' => $events->pluck('members_count')->map(fn ($v) => (int) $v)->all(),
                    'backgroundColor' => '#2F855A',
                ]],
                'labels' => $events->map(fn ($e) => Str::limit($e->title, 30))->all(),
            ];
        });
    }

    protected function getOptions(): array
    {
        return $this->analyticsBaseOptions([
            'indexAxis' => 'y',
            'plugins' => ['legend' => ['display' => false]],
        ]);
    }
}
