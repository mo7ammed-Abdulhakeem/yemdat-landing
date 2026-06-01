<?php

namespace App\Http\Controllers;

class FallbackController extends Controller
{
    /**
     * True 404 handler that still runs through the web middleware group, so the
     * session/locale is active and the 404 page renders localized (EN/AR).
     */
    public function __invoke()
    {
        abort(404);
    }
}
