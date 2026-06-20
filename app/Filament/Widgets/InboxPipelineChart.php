<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithAnalytics;
use App\Models\Contact;
use App\Models\TrainerRequest;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

/**
 * Support backlog at a glance: contact messages and trainer applications
 * grouped by workflow status (new / replied / closed).
 */
class InboxPipelineChart extends ChartWidget
{
    use InteractsWithAnalytics;

    protected int|string|array $columnSpan = ['default' => 'full', 'xl' => 2];

    protected ?string $maxHeight = '260px';

    public function getHeading(): string|Htmlable|null
    {
        return __('analytics.charts.pipeline.heading');
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
        return $this->analyticsCache('inbox-pipeline', function (): array {
            $contacts = Contact::selectRaw('status, COUNT(*) as c')->groupBy('status')->pluck('c', 'status');
            $trainers = TrainerRequest::selectRaw('status, COUNT(*) as c')->groupBy('status')->pluck('c', 'status');

            $statuses = ['new', 'replied', 'closed'];

            return [
                'datasets' => [
                    [
                        'label' => __('analytics.charts.pipeline.contacts'),
                        'data' => array_map(fn (string $s): int => (int) ($contacts[$s] ?? 0), $statuses),
                        'backgroundColor' => '#C88D16',
                    ],
                    [
                        'label' => __('analytics.charts.pipeline.trainer_requests'),
                        'data' => array_map(fn (string $s): int => (int) ($trainers[$s] ?? 0), $statuses),
                        'backgroundColor' => '#593E2D',
                    ],
                ],
                'labels' => [
                    __('global.status_new'),
                    __('global.status_replied'),
                    __('global.status_closed'),
                ],
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
