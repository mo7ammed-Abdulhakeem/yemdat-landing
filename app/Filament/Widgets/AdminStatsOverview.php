<?php

namespace App\Filament\Widgets;

use App\Models\Certificate;
use App\Models\Contact;
use App\Models\Event;
use App\Models\Member;
use App\Models\Post;
use App\Models\TrainerRequest;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class AdminStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = -3;

    public static function canView(): bool
    {
        return (bool) (auth()->user()?->hasPermission('analytics'));
    }

    protected function getStats(): array
    {
        $last30 = Member::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        $prev30 = Member::whereBetween('created_at', [Carbon::now()->subDays(60), Carbon::now()->subDays(30)])->count();
        $growth = $prev30 > 0
            ? (int) round((($last30 - $prev30) / $prev30) * 100)
            : ($last30 > 0 ? 100 : 0);
        $last7 = Member::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        $registrations = DB::table('event_member')->count();

        return [
            Stat::make('Members', Member::count())
                ->description($last30.' new (30d) · '.($growth >= 0 ? '+' : '').$growth.'% vs prev')
                ->descriptionIcon($growth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($growth >= 0 ? 'success' : 'danger'),
            Stat::make('New members (7d)', $last7)
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('primary'),
            Stat::make('Events', Event::count())
                ->description(Event::where('is_active', true)->count().' active')
                ->color('info'),
            Stat::make('Event registrations', $registrations)
                ->descriptionIcon('heroicon-m-ticket')
                ->color('info'),
            Stat::make('Certificates issued', Certificate::whereNull('revoked_at')->count())
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success'),
            Stat::make('Published posts', Post::where('is_published', true)->count())
                ->color('success'),
            Stat::make('Trainer requests', TrainerRequest::count())
                ->color('warning'),
            Stat::make('Contact messages', Contact::count()),
        ];
    }
}
