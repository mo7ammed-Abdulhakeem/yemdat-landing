<?php

namespace App\Filament\Concerns;

use Illuminate\Database\Eloquent\Model;

/**
 * Gates a Filament resource on the app's granular admin permissions
 * (User::hasPermission / isSuperAdmin). Each resource declares its key via
 * permissionKey(); return null to restrict the resource to super admins only.
 */
trait AuthorizesViaPermission
{
    abstract protected static function permissionKey(): ?string;

    protected static function userMayManage(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        $key = static::permissionKey();

        return $key === null ? $user->isSuperAdmin() : (bool) $user->hasPermission($key);
    }

    public static function canViewAny(): bool
    {
        return static::userMayManage();
    }

    public static function canCreate(): bool
    {
        return static::userMayManage();
    }

    public static function canView(Model $record): bool
    {
        return static::userMayManage();
    }

    public static function canEdit(Model $record): bool
    {
        return static::userMayManage();
    }

    public static function canDelete(Model $record): bool
    {
        return static::userMayManage();
    }

    public static function canDeleteAny(): bool
    {
        return static::userMayManage();
    }
}
