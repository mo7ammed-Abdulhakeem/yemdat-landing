<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Broadcast daily send limit
    |--------------------------------------------------------------------------
    |
    | Maximum number of broadcast emails ProcessBroadcastJob sends per run (per
    | day). Hostinger shared hosting caps outbound mail at ~100/day, so the job
    | chunks under that and reschedules. Read via config (not env()) so the app
    | stays correct under `php artisan config:cache` in production.
    |
    */

    'broadcast_daily_limit' => (int) env('BROADCAST_DAILY_LIMIT', 80),

];
