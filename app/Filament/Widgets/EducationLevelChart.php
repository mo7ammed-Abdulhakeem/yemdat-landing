<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithAnalytics;
use App\Models\Member;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

class EducationLevelChart extends ChartWidget
{
    use InteractsWithAnalytics;

    protected int|string|array $columnSpan = ['default' => 'full', 'md' => 1, 'xl' => 2];

    protected ?string $maxHeight = '260px';

    public function getHeading(): string|Htmlable|null
    {
        return __('analytics.charts.education.heading');
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
        return $this->analyticsCache('education', function (): array {
            $rows = Member::selectRaw("COALESCE(NULLIF(education_level, ''), 'unspecified') as e, COUNT(*) as c")
                ->groupBy('e')
                ->orderByDesc('c')
                ->pluck('c', 'e');

            $labels = $rows->keys()
                ->map(fn (string $e): string => match (strtolower($e)) {
                    'high school' => __('analytics.common.education.high_school'),
                    'diploma' => __('analytics.common.education.diploma'),
                    "bachelor's degree" => __('analytics.common.education.bachelor'),
                    "master's degree" => __('analytics.common.education.master'),
                    'phd' => __('analytics.common.education.phd'),
                    'other' => __('analytics.common.education.other'),
                    'unspecified' => __('analytics.common.unspecified'),
                    default => $e,
                })
                ->all();

            return [
                'datasets' => [[
                    'label' => __('analytics.common.members'),
                    'data' => $rows->values()->all(),
                    'backgroundColor' => '#C88D16',
                ]],
                'labels' => $labels,
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
