<?php

namespace App\Http\Middleware;

use App\Support\PageVisibility;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 404s requests to a managed public page (config/pages.php) that an admin has
 * turned off. No-op for every unmanaged route (admin/trainer/member panels,
 * core pages, etc.), so it's safe to append to the whole web group.
 */
class EnsurePageActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = PageVisibility::keyForRoute($request->route()?->getName());

        if ($key !== null && ! PageVisibility::isActive($key)) {
            abort(404);
        }

        return $next($request);
    }
}
