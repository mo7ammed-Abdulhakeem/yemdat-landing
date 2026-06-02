<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AdminStatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;

/**
 * Home dashboard — kept light: just the KPI overview. The full charts live on the
 * dedicated {@see AnalyticsDashboard} page.
 */
class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            AdminStatsOverview::class,
        ];
    }
}
