<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class MemberGrowthChart extends ChartWidget
{
    protected ?string $heading = 'Member growth (last 12 months)';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return (bool) (auth()->user()?->hasPermission('analytics'));
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $start = Carbon::now()->startOfMonth()->subMonths(11);

        $counts = Member::where('created_at', '>=', $start)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, COUNT(*) as c")
            ->groupBy('ym')
            ->pluck('c', 'ym');

        $labels = [];
        $data = [];
        for ($i = 0; $i < 12; $i++) {
            $month = $start->copy()->addMonths($i);
            $labels[] = $month->format('M Y');
            $data[] = (int) ($counts[$month->format('Y-m')] ?? 0);
        }

        return [
            'datasets' => [[
                'label' => 'New members',
                'data' => $data,
                'borderColor' => '#593E2D',
                'backgroundColor' => 'rgba(242, 203, 87, 0.25)',
                'fill' => true,
                'tension' => 0.3,
            ]],
            'labels' => $labels,
        ];
    }
}
