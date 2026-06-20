<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithAnalytics;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\DB;

class EventRegistrationsChart extends ChartWidget
{
    use InteractsWithAnalytics;

    protected int|string|array $columnSpan = ['default' => 'full', 'xl' => 2];

    protected ?string $maxHeight = '280px';

    public function getHeading(): string|Htmlable|null
    {
        return __('analytics.charts.registrations.heading');
    }

    public function getDescription(): string|Htmlable|null
    {
        return $this->analyticsRangeLabel();
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        return $this->analyticsCache('event-registrations', function (): array {
            [$start, $end] = $this->analyticsRange();
            $series = $this->analyticsTimeSeries(DB::table('event_member'), 'created_at', $start, $end);

            return [
                'datasets' => [[
                    'label' => __('analytics.charts.registrations.dataset'),
                    'data' => $series['data'],
                    'borderColor' => '#2B6CB0',
                    'backgroundColor' => 'rgba(43, 108, 176, 0.15)',
                    'fill' => true,
                    'tension' => 0.3,
                ]],
                'labels' => $series['labels'],
            ];
        });
    }

    protected function getOptions(): array
    {
        return $this->analyticsBaseOptions([
            'plugins' => ['legend' => ['display' => false]],
        ]);
    }
}
