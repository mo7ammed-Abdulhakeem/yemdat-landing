<?php

namespace App\Support;

use App\Models\User;
use Filament\Notifications\Notification;

/**
 * Sends Filament database notifications (the admin bell) to staff accounts.
 * Permissions live in a JSON column, so we load the small set of panel users
 * and filter in PHP via hasPermission() (super admins always qualify).
 */
class AdminNotifier
{
    public static function notifyPermission(string $permission, string $title, string $body): void
    {
        $recipients = static::panelUsers()
            ->filter(fn (User $user) => $user->hasPermission($permission));

        static::send($recipients, $title, $body);
    }

    public static function notifyAll(string $title, string $body): void
    {
        static::send(static::panelUsers(), $title, $body);
    }

    /** @return \Illuminate\Support\Collection<int, User> */
    protected static function panelUsers(): \Illuminate\Support\Collection
    {
        return User::query()->whereIn('role', ['admin', 'super_admin'])->get();
    }

    protected static function send(\Illuminate\Support\Collection $recipients, string $title, string $body): void
    {
        if ($recipients->isEmpty()) {
            return;
        }

        Notification::make()
            ->title($title)
            ->body($body)
            ->info()
            ->sendToDatabase($recipients);
    }
}
