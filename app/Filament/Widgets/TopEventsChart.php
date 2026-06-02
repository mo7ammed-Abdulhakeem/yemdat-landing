<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Str;

class TopEventsChart extends ChartWidget
{
    protected ?string $heading = 'Top events by registrations';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 6;

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
        $events = Event::withCount('members')
            ->orderByDesc('members_count')
            ->limit(10)
            ->get();

        return [
            'datasets' => [[
                'label' => 'Registrations',
                'data' => $events->pluck('members_count')->map(fn ($v) => (int) $v)->all(),
                'backgroundColor' => '#2F855A',
            ]],
            'labels' => $events->map(fn ($e) => Str::limit($e->title, 30))->all(),
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
