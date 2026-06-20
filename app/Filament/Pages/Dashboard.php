<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AdminStatsOverview;
use App\Filament\Widgets\MemberGrowthChart;
use App\Filament\Widgets\UpcomingEventsTable;
use Filament\Pages\Dashboard as BaseDashboard;

/**
 * Home dashboard — kept light: the KPI overview plus a growth pulse and the
 * next events. The full sectioned analytics live on {@see AnalyticsDashboard}.
 * No filters form here; widgets fall back to their default range.
 */
class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            AdminStatsOverview::class,
            MemberGrowthChart::class,
            UpcomingEventsTable::class,
        ];
    }

    /**
     * @return int|array<string, ?int>
     */
    public function getColumns(): int|array
    {
        return ['default' => 1, 'md' => 2, 'xl' => 4];
    }
}
