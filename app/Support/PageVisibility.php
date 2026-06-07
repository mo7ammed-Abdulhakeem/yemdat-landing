<?php

namespace App\Support;

use App\Models\Setting;

/**
 * Reads/derives whether a managed public page (config/pages.php) is currently
 * active. State lives in the `settings` table under "page_{key}_active" ('1'/'0');
 * until an admin saves a value, the config `default` applies.
 */
class PageVisibility
{
    /** @var array<string,string>|null route name => page key (config is immutable per process) */
    protected static ?array $routeMap = null;

    public static function isActive(string $key): bool
    {
        $pages = config('pages', []);

        // Unmanaged pages are always active.
        if (! isset($pages[$key])) {
            return true;
        }

        $default = (bool) ($pages[$key]['default'] ?? true);
        $value = Setting::get("page_{$key}_active");

        return $value === null ? $default : $value === '1';
    }

    /**
     * Map a route name to the managed page key that owns it (or null).
     */
    public static function keyForRoute(?string $routeName): ?string
    {
        if (! $routeName) {
            return null;
        }

        if (static::$routeMap === null) {
            static::$routeMap = [];
            foreach (config('pages', []) as $key => $cfg) {
                foreach ($cfg['routes'] ?? [] as $route) {
                    static::$routeMap[$route] = $key;
                }
            }
        }

        return static::$routeMap[$routeName] ?? null;
    }

    /**
     * All managed pages with labels + current active state (for the admin form).
     *
     * @return array<string,array{label_en:string,label_ar:string,active:bool}>
     */
    public static function all(): array
    {
        $out = [];
        foreach (config('pages', []) as $key => $cfg) {
            $out[$key] = [
                'label_en' => $cfg['label_en'] ?? $key,
                'label_ar' => $cfg['label_ar'] ?? $key,
                'active' => static::isActive($key),
            ];
        }

        return $out;
    }
}
