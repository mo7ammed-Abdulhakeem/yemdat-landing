<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use Filament\Widgets\ChartWidget;

class GenderDistributionChart extends ChartWidget
{
    protected ?string $heading = 'Members by gender';

    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        return (bool) (auth()->user()?->hasPermission('analytics'));
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $rows = Member::selectRaw("COALESCE(NULLIF(gender, ''), 'Not specified') as g, COUNT(*) as c")
            ->groupBy('g')
            ->pluck('c', 'g');

        return [
            'datasets' => [[
                'data' => $rows->values()->all(),
                'backgroundColor' => ['#593E2D', '#F2CB57', '#C88D16', '#6B5847'],
            ]],
            'labels' => $rows->keys()->all(),
        ];
    }
}
