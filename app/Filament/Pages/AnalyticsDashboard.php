<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AdminStatsOverview;
use App\Filament\Widgets\BroadcastEngagementTable;
use App\Filament\Widgets\BroadcastStats;
use App\Filament\Widgets\CertificatesIssuedChart;
use App\Filament\Widgets\EducationLevelChart;
use App\Filament\Widgets\EventEngagementStats;
use App\Filament\Widgets\EventFormatBreakdownChart;
use App\Filament\Widgets\EventOutcomesChart;
use App\Filament\Widgets\EventRegistrationsChart;
use App\Filament\Widgets\GenderDistributionChart;
use App\Filament\Widgets\InboxPipelineChart;
use App\Filament\Widgets\MemberFunnelChart;
use App\Filament\Widgets\MemberGrowthChart;
use App\Filament\Widgets\MembershipTierChart;
use App\Filament\Widgets\SpecialtyDistributionChart;
use App\Filament\Widgets\TopCountriesChart;
use App\Filament\Widgets\TopEventsChart;
use App\Filament\Widgets\UpcomingEventsTable;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class AnalyticsDashboard extends BaseDashboard
{
    use HasFiltersForm;

    protected static string $routePath = '/analytics';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    // Sit directly under the home Dashboard (which is -2).
    protected static ?int $navigationSort = -1;

    public static function canAccess(): bool
    {
        return (bool) (auth()->user()?->hasPermission('analytics'));
    }

    public function getTitle(): string|Htmlable
    {
        return __('analytics.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('analytics.title');
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('range')
                ->label(__('analytics.filters.range'))
                ->options([
                    'last_7' => __('analytics.filters.ranges.last_7'),
                    'last_30' => __('analytics.filters.ranges.last_30'),
                    'last_90' => __('analytics.filters.ranges.last_90'),
                    'last_365' => __('analytics.filters.ranges.last_365'),
                    'all' => __('analytics.filters.ranges.all'),
                    'custom' => __('analytics.filters.ranges.custom'),
                ])
                ->default('last_90')
                ->selectablePlaceholder(false),
            DatePicker::make('from')
                ->label(__('analytics.filters.from'))
                ->maxDate(now())
                ->visible(fn (Get $get): bool => $get('range') === 'custom'),
            DatePicker::make('until')
                ->label(__('analytics.filters.until'))
                ->maxDate(now())
                ->visible(fn (Get $get): bool => $get('range') === 'custom'),
        ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            $this->getFiltersFormContentComponent(),

            // KPI summary row — outside any section so it reads first.
            Grid::make(1)
                ->schema(fn (): array => $this->getWidgetsSchemaComponents([
                    AdminStatsOverview::class,
                ])),

            $this->section('growth', Heroicon::OutlinedArrowTrendingUp, [
                MemberGrowthChart::class,
                MemberFunnelChart::class,
            ]),

            $this->section('events', Heroicon::OutlinedCalendarDays, [
                EventEngagementStats::class,
                EventRegistrationsChart::class,
                EventFormatBreakdownChart::class,
                EventOutcomesChart::class,
                CertificatesIssuedChart::class,
                TopEventsChart::class,
                UpcomingEventsTable::class,
            ]),

            $this->section('demographics', Heroicon::OutlinedUsers, [
                GenderDistributionChart::class,
                MembershipTierChart::class,
                EducationLevelChart::class,
                TopCountriesChart::class,
                SpecialtyDistributionChart::class,
            ])->collapsed(),

            $this->section('communications', Heroicon::OutlinedEnvelopeOpen, [
                BroadcastStats::class,
                BroadcastEngagementTable::class,
            ]),

            $this->section('operations', Heroicon::OutlinedInboxStack, [
                InboxPipelineChart::class,
            ]),
        ]);
    }

    /**
     * @return int|array<string, ?int>
     */
    public function getColumns(): int|array
    {
        return ['default' => 1, 'md' => 2, 'xl' => 4];
    }

    /**
     * A collapsible dashboard section holding a responsive grid of widgets.
     *
     * @param  array<class-string>  $widgets
     */
    protected function section(string $key, Heroicon $icon, array $widgets): Section
    {
        return Section::make(__("analytics.sections.$key.heading"))
            ->description(__("analytics.sections.$key.description"))
            ->icon($icon)
            ->collapsible()
            ->schema([
                Grid::make($this->getColumns())
                    ->schema(fn (): array => $this->getWidgetsSchemaComponents($widgets)),
            ]);
    }

    public function getWidgets(): array
    {
        // Layout lives in content(); this list exists for completeness/tests.
        return [
            AdminStatsOverview::class,
            MemberGrowthChart::class,
            MemberFunnelChart::class,
            EventEngagementStats::class,
            EventRegistrationsChart::class,
            EventFormatBreakdownChart::class,
            EventOutcomesChart::class,
            CertificatesIssuedChart::class,
            TopEventsChart::class,
            UpcomingEventsTable::class,
            GenderDistributionChart::class,
            MembershipTierChart::class,
            EducationLevelChart::class,
            TopCountriesChart::class,
            SpecialtyDistributionChart::class,
            BroadcastStats::class,
            BroadcastEngagementTable::class,
            InboxPipelineChart::class,
        ];
    }
}
