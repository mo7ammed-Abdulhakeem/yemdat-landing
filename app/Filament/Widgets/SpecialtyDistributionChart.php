<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use App\Models\Specialty;
use Filament\Widgets\ChartWidget;

class SpecialtyDistributionChart extends ChartWidget
{
    protected ?string $heading = 'Members by specialty';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

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
        $names = Specialty::ordered()->get()->keyBy('slug');

        $rows = Member::selectRaw('specialty, COUNT(*) as c')
            ->groupBy('specialty')
            ->orderByDesc('c')
            ->get();

        $labels = [];
        $data = [];
        foreach ($rows as $row) {
            $labels[] = optional($names->get($row->specialty))->name ?? ($row->specialty ?: 'Unspecified');
            $data[] = (int) $row->c;
        }

        return [
            'datasets' => [[
                'label' => 'Members',
                'data' => $data,
                'backgroundColor' => '#593E2D',
            ]],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        // Horizontal bars read better with many categories.
        return [
            'indexAxis' => 'y',
            'plugins' => ['legend' => ['display' => false]],
        ];
    }
}
