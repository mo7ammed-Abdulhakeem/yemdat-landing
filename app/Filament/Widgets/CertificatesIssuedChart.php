<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithAnalytics;
use App\Models\Certificate;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

/**
 * Issued vs emailed certificates over time — a visible gap between the two
 * bars means a delivery backlog that needs clearing.
 */
class CertificatesIssuedChart extends ChartWidget
{
    use InteractsWithAnalytics;

    protected int|string|array $columnSpan = ['default' => 'full', 'xl' => 2];

    protected ?string $maxHeight = '280px';

    public function getHeading(): string|Htmlable|null
    {
        return __('analytics.charts.certificates.heading');
    }

    public function getDescription(): string|Htmlable|null
    {
        return $this->analyticsRangeLabel();
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        return $this->analyticsCache('certificates-issued', function (): array {
            [$start, $end] = $this->analyticsRange();

            // Resolve "all time" to the first issuance so both series share buckets.
            if ($start === null) {
                $earliest = Certificate::whereNull('revoked_at')->min('issued_at');
                $start = $earliest
                    ? Carbon::parse($earliest)->startOfDay()
                    : $end->copy()->subMonths(11)->startOfMonth();
            }

            $issued = $this->analyticsTimeSeries(Certificate::whereNull('revoked_at'), 'issued_at', $start, $end);
            $emailed = $this->analyticsTimeSeries(Certificate::whereNull('revoked_at'), 'emailed_at', $start, $end);

            return [
                'datasets' => [
                    [
                        'label' => __('analytics.charts.certificates.issued'),
                        'data' => $issued['data'],
                        'backgroundColor' => '#593E2D',
                    ],
                    [
                        'label' => __('analytics.charts.certificates.emailed'),
                        'data' => $emailed['data'],
                        'backgroundColor' => '#2F855A',
                    ],
                ],
                'labels' => $issued['labels'],
            ];
        });
    }

    protected function getOptions(): array
    {
        return $this->analyticsBaseOptions([
            'plugins' => ['legend' => ['position' => 'bottom']],
        ]);
    }
}
