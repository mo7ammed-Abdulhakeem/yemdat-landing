<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithAnalytics;
use App\Models\Member;
use App\Models\MembershipTier;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

class MembershipTierChart extends ChartWidget
{
    use InteractsWithAnalytics;

    protected int|string|array $columnSpan = ['default' => 'full', 'md' => 1, 'xl' => 1];

    protected ?string $maxHeight = '220px';

    public function getHeading(): string|Htmlable|null
    {
        return __('analytics.charts.tiers.heading');
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
        return $this->analyticsCache('tiers', function (): array {
            $names = MembershipTier::all()->keyBy('slug');

            $rows = Member::selectRaw("COALESCE(NULLIF(membership_type, ''), 'unspecified') as t, COUNT(*) as c")
                ->groupBy('t')
                ->orderByDesc('c')
                ->pluck('c', 't');

            $labels = $rows->keys()
                ->map(fn (string $t): string => optional($names->get($t))->name
                    ?? ($t === 'unspecified' ? __('analytics.common.unspecified') : $t))
                ->all();

            return [
                'datasets' => [[
                    'data' => $rows->values()->all(),
                    'backgroundColor' => ['#593E2D', '#F2CB57', '#C88D16', '#6B5847', '#2F855A'],
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
