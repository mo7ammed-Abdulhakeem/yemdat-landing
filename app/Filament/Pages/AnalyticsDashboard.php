<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AdminStatsOverview;
use App\Filament\Widgets\EducationLevelChart;
use App\Filament\Widgets\GenderDistributionChart;
use App\Filament\Widgets\MemberGrowthChart;
use App\Filament\Widgets\SpecialtyDistributionChart;
use App\Filament\Widgets\TopCountriesChart;
use App\Filament\Widgets\TopEventsChart;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;

class AnalyticsDashboard extends BaseDashboard
{
    protected static string $routePath = '/analytics';

    protected static ?string $title = 'Analytics';

    protected static ?string $navigationLabel = 'Analytics';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    // Sit directly under the home Dashboard (which is -2).
    protected static ?int $navigationSort = -1;

    public static function canAccess(): bool
    {
        return (bool) (auth()->user()?->hasPermission('analytics'));
    }

    public function getWidgets(): array
    {
        return [
            AdminStatsOverview::class,
            MemberGrowthChart::class,
            SpecialtyDistributionChart::class,
            GenderDistributionChart::class,
            EducationLevelChart::class,
            TopCountriesChart::class,
            TopEventsChart::class,
        ];
    }
}
