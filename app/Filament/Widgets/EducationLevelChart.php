<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use Filament\Widgets\ChartWidget;

class EducationLevelChart extends ChartWidget
{
    protected ?string $heading = 'Members by education level';

    protected static ?int $sort = 4;

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
        $rows = Member::selectRaw("COALESCE(NULLIF(education_level, ''), 'Not specified') as e, COUNT(*) as c")
            ->groupBy('e')
            ->orderByDesc('c')
            ->pluck('c', 'e');

        return [
            'datasets' => [[
                'label' => 'Members',
                'data' => $rows->values()->all(),
                'backgroundColor' => '#C88D16',
            ]],
            'labels' => $rows->keys()->all(),
        ];
    }

    protected function getOptions(): array
    {
        return ['plugins' => ['legend' => ['display' => false]]];
    }
}
