<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use Filament\Widgets\ChartWidget;

class TopCountriesChart extends ChartWidget
{
    protected ?string $heading = 'Top countries';

    protected static ?int $sort = 5;

    public static function canView(): bool
    {
        return (bool) (auth()->user()?->hasPermission('analytics'));
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $rows = Member::selectRaw("COALESCE(NULLIF(country, ''), 'Not specified') as country, COUNT(*) as c")
            ->groupBy('country')
            ->orderByDesc('c')
            ->limit(10)
            ->pluck('c', 'country');

        return [
            'datasets' => [[
                'label' => 'Members',
                'data' => $rows->values()->all(),
                'backgroundColor' => '#2B6CB0',
            ]],
            'labels' => $rows->keys()->all(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins' => ['legend' => ['display' => false]],
        ];
    }
}
