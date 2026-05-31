<?php

namespace App\Filament\Widgets;

use App\Models\Contact;
use App\Models\EmailBroadcast;
use App\Models\Event;
use App\Models\Member;
use App\Models\Post;
use App\Models\TrainerRequest;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = -3;

    public static function canView(): bool
    {
        return (bool) (auth()->user()?->hasPermission('analytics'));
    }

    protected function getStats(): array
    {
        $newMembers = Member::where('created_at', '>=', Carbon::now()->subDays(30))->count();

        return [
            Stat::make('Members', Member::count())
                ->description($newMembers.' new in last 30 days')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('primary'),
            Stat::make('Events', Event::count())
                ->description(Event::where('is_active', true)->count().' active')
                ->color('info'),
            Stat::make('Published Posts', Post::where('is_published', true)->count())
                ->color('success'),
            Stat::make('Trainer Requests', TrainerRequest::count())
                ->color('warning'),
            Stat::make('Contact Messages', Contact::count()),
            Stat::make('Broadcasts Sent', EmailBroadcast::where('status', 'sent')->count()),
        ];
    }
}
