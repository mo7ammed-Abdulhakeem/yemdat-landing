<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithAnalytics;
use App\Models\Member;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

class GenderDistributionChart extends ChartWidget
{
    use InteractsWithAnalytics;

    protected int|string|array $columnSpan = ['default' => 'full', 'md' => 1, 'xl' => 1];

    protected ?string $maxHeight = '220px';

    public function getHeading(): string|Htmlable|null
    {
        return __('analytics.charts.gender.heading');
    }

    public function getDescription(): string|Htmlable|null
    {
        return __('analytics.common.all_time');
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        return $this->analyticsCache('gender', function (): array {
            $rows = Member::selectRaw("COALESCE(NULLIF(gender, ''), 'unspecified') as g, COUNT(*) as c")
                ->groupBy('g')
                ->pluck('c', 'g');

            $labels = $rows->keys()
                ->map(fn (string $g): string => match (strtolower($g)) {
                    'male' => __('analytics.common.gender.male'),
                    'female' => __('analytics.common.gender.female'),
                    'unspecified' => __('analytics.common.unspecified'),
                    default => $g,
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
