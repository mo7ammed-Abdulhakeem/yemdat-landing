<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithAnalytics;
use App\Models\Member;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

/**
 * Activation/deliverability funnel: all members → verified email →
 * verified AND still subscribed (actually reachable by broadcasts).
 */
class MemberFunnelChart extends ChartWidget
{
    use InteractsWithAnalytics;

    protected int|string|array $columnSpan = ['default' => 'full', 'md' => 1, 'xl' => 1];

    protected ?string $maxHeight = '280px';

    public function getHeading(): string|Htmlable|null
    {
        return __('analytics.charts.funnel.heading');
    }

    public function getDescription(): string|Htmlable|null
    {
        return __('analytics.common.all_time');
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        return $this->analyticsCache('member-funnel', function (): array {
            $total = Member::count();
            $verified = Member::whereNotNull('email_verified_at')->count();
            $reachable = Member::whereNotNull('email_verified_at')->whereNull('unsubscribed_at')->count();

            return [
                'datasets' => [[
                    'label' => __('analytics.common.members'),
                    'data' => [$total, $verified, $reachable],
                    'backgroundColor' => ['#593E2D', '#C88D16', '#2F855A'],
                ]],
                'labels' => [
                    __('analytics.charts.funnel.total'),
                    __('analytics.charts.funnel.verified'),
                    __('analytics.charts.funnel.reachable'),
                ],
            ];
        });
    }

    protected function getOptions(): array
    {
        return $this->analyticsBaseOptions([
            'indexAxis' => 'y',
            'plugins' => ['legend' => ['display' => false]],
        ]);
    }
}
