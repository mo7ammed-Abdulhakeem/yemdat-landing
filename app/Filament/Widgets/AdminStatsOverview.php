<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\InteractsWithAnalytics;
use App\Models\Certificate;
use App\Models\Contact;
use App\Models\Event;
use App\Models\Member;
use App\Models\TrainerRequest;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;

class AdminStatsOverview extends StatsOverviewWidget
{
    use InteractsWithAnalytics;

    protected function getStats(): array
    {
        // Cache the numbers only — Stat objects may not serialize.
        $d = $this->analyticsCache('kpis', function (): array {
            [$start, $end] = $this->analyticsRange();

            $inRange = fn ($query, string $column = 'created_at') => $start
                ? $query->whereBetween($column, [$start, $end])
                : $query;

            $totalMembers = Member::count();
            $newMembers = $inRange(Member::query())->count();

            $previousGrowth = null;
            if ($start) {
                // Compare against the previous window of equal length.
                $windowDays = (int) $start->diffInDays($end) + 1;
                $previous = Member::whereBetween('created_at', [
                    $start->copy()->subDays($windowDays),
                    $start->copy()->subSecond(),
                ])->count();
                $previousGrowth = $previous > 0
                    ? (int) round((($newMembers - $previous) / $previous) * 100)
                    : ($newMembers > 0 ? 100 : 0);
            }

            $verified = Member::whereNotNull('email_verified_at')->count();

            return [
                'total_members' => $totalMembers,
                'new_members' => $newMembers,
                'growth' => $previousGrowth,
                'member_spark' => $this->analyticsSparkline(Member::query(), 'created_at'),
                'new_member_spark' => $this->analyticsSparkline(Member::query(), 'created_at', 14, 'day'),
                'registrations' => $inRange(DB::table('event_member'))->count(),
                'registration_spark' => $this->analyticsSparkline(DB::table('event_member'), 'created_at'),
                'active_events' => Event::where('is_active', true)->count(),
                'upcoming_events' => Event::where('is_active', true)->where('start_date', '>=', now())->count(),
                'certificates' => $inRange(Certificate::whereNull('revoked_at'), 'issued_at')->count(),
                'not_emailed' => Certificate::whereNull('revoked_at')->whereNull('emailed_at')->count(),
                'verified' => $verified,
                'verified_rate' => $totalMembers > 0 ? (int) round(($verified / $totalMembers) * 100) : 0,
                'new_contacts' => Contact::where('status', 'new')->count(),
                'new_trainer_requests' => TrainerRequest::where('status', 'new')->count(),
            ];
        });

        $rangeLabel = $this->analyticsRangeLabel();

        $newMembersStat = Stat::make(__('analytics.stats.new_members'), Number::format($d['new_members']))
            ->chart($d['new_member_spark'])
            ->chartColor('success');

        if ($d['growth'] !== null) {
            $newMembersStat
                ->description(__('analytics.stats.vs_previous', ['percent' => ($d['growth'] >= 0 ? '+' : '').$d['growth']]))
                ->descriptionIcon($d['growth'] >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($d['growth'] >= 0 ? 'success' : 'danger');
        } else {
            $newMembersStat->description($rangeLabel);
        }

        return [
            Stat::make(__('analytics.stats.members'), Number::format($d['total_members']))
                ->description(__('analytics.stats.new_in_range', ['count' => Number::format($d['new_members'])]).' · '.$rangeLabel)
                ->descriptionIcon('heroicon-m-user-plus')
                ->chart($d['member_spark'])
                ->chartColor('primary')
                ->color('primary'),
            $newMembersStat,
            Stat::make(__('analytics.stats.registrations'), Number::format($d['registrations']))
                ->description($rangeLabel)
                ->descriptionIcon('heroicon-m-ticket')
                ->chart($d['registration_spark'])
                ->chartColor('info')
                ->color('info'),
            Stat::make(__('analytics.stats.active_events'), Number::format($d['active_events']))
                ->description(__('analytics.stats.upcoming_count', ['count' => $d['upcoming_events']]))
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),
            Stat::make(__('analytics.stats.certificates'), Number::format($d['certificates']))
                ->description($d['not_emailed'] > 0
                    ? __('analytics.stats.not_emailed', ['count' => Number::format($d['not_emailed'])])
                    : $rangeLabel)
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color($d['not_emailed'] > 0 ? 'warning' : 'success'),
            Stat::make(__('analytics.stats.verified_rate'), $d['verified_rate'].'%')
                ->description(__('analytics.stats.verified_of_total', [
                    'verified' => Number::format($d['verified']),
                    'total' => Number::format($d['total_members']),
                ]))
                ->descriptionIcon('heroicon-m-check-badge')
                ->color($d['verified_rate'] >= 70 ? 'success' : 'warning'),
            Stat::make(__('analytics.stats.new_contacts'), Number::format($d['new_contacts']))
                ->description(__('analytics.stats.needs_reply'))
                ->descriptionIcon('heroicon-m-envelope')
                ->color('warning'),
            Stat::make(__('analytics.stats.new_trainer_requests'), Number::format($d['new_trainer_requests']))
                ->description(__('analytics.stats.needs_reply'))
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),
        ];
    }
}
