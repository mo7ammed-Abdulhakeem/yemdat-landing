<?php

namespace App\Filament\Widgets\Concerns;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Closure;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\Cache;

/**
 * Shared behaviour for the admin analytics widgets: permission gate,
 * no-polling (Filament defaults to a 5s wire:poll), the dashboard
 * date-range filter, short-lived caching, and time-series helpers.
 *
 * Widgets also render on pages without a filters form (the home
 * Dashboard) — there $pageFilters is null and the default range applies.
 */
trait InteractsWithAnalytics
{
    use InteractsWithPageFilters;

    public const DEFAULT_RANGE = 'last_90';

    /**
     * Disable Filament's default 5s wire:poll. A method override because a
     * trait can't redeclare the inherited $pollingInterval property with a
     * different default (PHP fatal).
     */
    protected function getPollingInterval(): ?string
    {
        return null;
    }

    public static function canView(): bool
    {
        return (bool) (auth()->user()?->hasPermission('analytics'));
    }

    /**
     * Resolve the page filters into [start, end]. A null start means "all time".
     *
     * @return array{0: ?Carbon, 1: Carbon}
     */
    protected function analyticsRange(): array
    {
        $filters = $this->pageFilters ?? [];
        $range = $filters['range'] ?? self::DEFAULT_RANGE;
        $end = Carbon::now()->endOfDay();

        return match ($range) {
            'last_7' => [Carbon::now()->subDays(6)->startOfDay(), $end],
            'last_30' => [Carbon::now()->subDays(29)->startOfDay(), $end],
            'last_365' => [Carbon::now()->subDays(364)->startOfDay(), $end],
            'all' => [null, $end],
            'custom' => [
                filled($filters['from'] ?? null) ? Carbon::parse($filters['from'])->startOfDay() : Carbon::now()->subDays(89)->startOfDay(),
                filled($filters['until'] ?? null) ? Carbon::parse($filters['until'])->endOfDay() : $end,
            ],
            default => [Carbon::now()->subDays(89)->startOfDay(), $end],
        };
    }

    /** Translated label of the currently selected range, for widget descriptions. */
    protected function analyticsRangeLabel(): string
    {
        $range = ($this->pageFilters ?? [])['range'] ?? self::DEFAULT_RANGE;

        if ($range === 'custom') {
            [$start, $end] = $this->analyticsRange();

            return $start->translatedFormat('d M Y').' – '.$end->translatedFormat('d M Y');
        }

        return __('analytics.filters.ranges.'.$range);
    }

    /** Bucket size that keeps the chart readable for the range length. */
    protected function analyticsGranularity(?Carbon $start, Carbon $end): string
    {
        if ($start === null) {
            return 'month';
        }

        $days = (int) $start->diffInDays($end);

        return $days <= 31 ? 'day' : ($days <= 182 ? 'week' : 'month');
    }

    /**
     * Zero-filled time series of COUNT(*) per bucket for the given query.
     * Clones the query, so pass it pre-scoped but without date constraints.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @return array{labels: array<int, string>, data: array<int, int>}
     */
    protected function analyticsTimeSeries($query, string $column, ?Carbon $start, Carbon $end): array
    {
        if ($start === null) {
            $earliest = (clone $query)->min($column);
            $start = $earliest
                ? Carbon::parse($earliest)->startOfDay()
                : $end->copy()->subMonths(11)->startOfMonth();
        }

        $granularity = $this->analyticsGranularity($start, $end);

        $expression = match ($granularity) {
            'day' => "DATE_FORMAT($column, '%Y-%m-%d')",
            // Bucket by the Monday of each week to match Carbon::startOfWeek().
            'week' => "DATE_FORMAT(DATE_SUB($column, INTERVAL WEEKDAY($column) DAY), '%Y-%m-%d')",
            default => "DATE_FORMAT($column, '%Y-%m')",
        };

        $counts = (clone $query)
            ->whereBetween($column, [$start, $end])
            ->selectRaw("$expression as bucket, COUNT(*) as c")
            ->groupBy('bucket')
            ->pluck('c', 'bucket');

        $cursor = match ($granularity) {
            'day' => $start->copy()->startOfDay(),
            // Pin to Monday: the SQL bucket uses WEEKDAY() (Monday-based), but
            // Carbon's startOfWeek() is locale-dependent (Saturday in Arabic).
            'week' => $start->copy()->startOfWeek(CarbonInterface::MONDAY),
            default => $start->copy()->startOfMonth(),
        };

        $labels = [];
        $data = [];

        while ($cursor->lte($end) && count($labels) < 400) {
            $key = $granularity === 'month' ? $cursor->format('Y-m') : $cursor->format('Y-m-d');
            $labels[] = $granularity === 'month'
                ? $cursor->translatedFormat('M Y')
                : $cursor->translatedFormat('d M');
            $data[] = (int) ($counts[$key] ?? 0);

            match ($granularity) {
                'day' => $cursor->addDay(),
                'week' => $cursor->addWeek(),
                default => $cursor->addMonth(),
            };
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Small zero-filled count series for Stat sparklines (fixed trailing window,
     * independent of the page filters).
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @return array<int, int>
     */
    protected function analyticsSparkline($query, string $column, int $buckets = 12, string $unit = 'week'): array
    {
        $end = Carbon::now();
        $start = $unit === 'day'
            ? $end->copy()->subDays($buckets - 1)->startOfDay()
            : $end->copy()->subWeeks($buckets - 1)->startOfWeek(CarbonInterface::MONDAY);

        $expression = $unit === 'day'
            ? "DATE_FORMAT($column, '%Y-%m-%d')"
            : "DATE_FORMAT(DATE_SUB($column, INTERVAL WEEKDAY($column) DAY), '%Y-%m-%d')";

        $counts = (clone $query)
            ->whereBetween($column, [$start, $end])
            ->selectRaw("$expression as bucket, COUNT(*) as c")
            ->groupBy('bucket')
            ->pluck('c', 'bucket');

        $out = [];
        $cursor = $start->copy();
        for ($i = 0; $i < $buckets; $i++) {
            $out[] = (int) ($counts[$cursor->format('Y-m-d')] ?? 0);
            $unit === 'day' ? $cursor->addDay() : $cursor->addWeek();
        }

        return $out;
    }

    /**
     * Cache expensive widget queries briefly. Keyed by the active filters and
     * locale, since cached payloads embed localized labels.
     */
    protected function analyticsCache(string $key, Closure $callback): mixed
    {
        return Cache::remember(
            'analytics.'.$key.'.'.md5(json_encode($this->pageFilters ?? [])).'.'.app()->getLocale(),
            300,
            $callback,
        );
    }

    /** Chart.js options shared by the analytics charts (RTL-aware legends/tooltips). */
    protected function analyticsBaseOptions(array $overrides = []): array
    {
        $rtl = app()->getLocale() === 'ar';

        return array_replace_recursive([
            'plugins' => [
                'legend' => ['rtl' => $rtl, 'textDirection' => $rtl ? 'rtl' : 'ltr'],
                'tooltip' => ['rtl' => $rtl, 'textDirection' => $rtl ? 'rtl' : 'ltr'],
            ],
        ], $overrides);
    }
}
