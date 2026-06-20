<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithAnalytics;
use App\Models\EmailBroadcast;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;

/**
 * Email program health over the selected period. Aggregates recipients in a
 * single query — never the per-row accessors on EmailBroadcast (N+1).
 */
class BroadcastStats extends StatsOverviewWidget
{
    use InteractsWithAnalytics;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $d = $this->analyticsCache('broadcast-stats', function (): array {
            [$start, $end] = $this->analyticsRange();

            $broadcasts = EmailBroadcast::where('status', 'sent')
                ->when($start, fn ($q) => $q->whereBetween('sent_at', [$start, $end]))
                ->count();

            $recipients = DB::table('email_broadcast_recipients as r')
                ->join('email_broadcasts as b', 'b.id', '=', 'r.broadcast_id')
                ->where('b.status', 'sent')
                ->when($start, fn ($q) => $q->whereBetween('b.sent_at', [$start, $end]))
                ->selectRaw('COUNT(*) as sent, COUNT(r.opened_at) as opened, COUNT(r.unsubscribed_at) as unsubscribed')
                ->first();

            return [
                'broadcasts' => $broadcasts,
                'sent' => (int) $recipients->sent,
                'opened' => (int) $recipients->opened,
                'unsubscribed' => (int) $recipients->unsubscribed,
            ];
        });

        $openRate = $d['sent'] > 0 ? (int) round(($d['opened'] / $d['sent']) * 100) : 0;
        $rangeLabel = $this->analyticsRangeLabel();

        return [
            Stat::make(__('analytics.stats.broadcasts_sent'), Number::format($d['broadcasts']))
                ->description($rangeLabel)
                ->descriptionIcon('heroicon-m-megaphone')
                ->color('primary'),
            Stat::make(__('analytics.stats.emails_delivered'), Number::format($d['sent']))
                ->description($rangeLabel)
                ->descriptionIcon('heroicon-m-paper-airplane')
                ->color('info'),
            Stat::make(__('analytics.stats.open_rate'), $openRate.'%')
                ->description(__('analytics.stats.opened_of_sent', [
                    'opened' => Number::format($d['opened']),
                    'sent' => Number::format($d['sent']),
                ]))
                ->descriptionIcon('heroicon-m-envelope-open')
                ->color($openRate >= 40 ? 'success' : ($openRate >= 20 ? 'warning' : 'danger')),
            Stat::make(__('analytics.stats.unsubscribes'), Number::format($d['unsubscribed']))
                ->description($rangeLabel)
                ->descriptionIcon('heroicon-m-user-minus')
                ->color($d['unsubscribed'] > 0 ? 'danger' : 'success'),
        ];
    }
}
