<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithAnalytics;
use App\Models\Member;
use App\Models\Specialty;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

class SpecialtyDistributionChart extends ChartWidget
{
    use InteractsWithAnalytics;

    protected int|string|array $columnSpan = ['default' => 'full', 'xl' => 2];

    protected ?string $maxHeight = '320px';

    public function getHeading(): string|Htmlable|null
    {
        return __('analytics.charts.specialty.heading');
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
        return $this->analyticsCache('specialty', function (): array {
            $names = Specialty::ordered()->get()->keyBy('slug');

            $rows = Member::selectRaw('specialty, COUNT(*) as c')
                ->groupBy('specialty')
                ->orderByDesc('c')
                ->get();

            $labels = [];
            $data = [];
            foreach ($rows as $row) {
                $labels[] = optional($names->get($row->specialty))->name
                    ?? ($row->specialty ?: __('analytics.common.unspecified'));
                $data[] = (int) $row->c;
            }

            return [
                'datasets' => [[
                    'label' => __('analytics.common.members'),
                    'data' => $data,
                    'backgroundColor' => '#593E2D',
                ]],
                'labels' => $labels,
            ];
        });
    }

    protected function getOptions(): array
    {
        // Horizontal bars read better with many categories.
        return $this->analyticsBaseOptions([
            'indexAxis' => 'y',
            'plugins' => ['legend' => ['display' => false]],
        ]);
    }
}
