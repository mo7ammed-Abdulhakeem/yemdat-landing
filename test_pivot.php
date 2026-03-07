<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$event = \App\Models\Event::first();
if ($event) {
    echo "Event ID: " . $event->id . "\n";
    echo "Reg Count: " . $event->members()->count() . "\n";

    $pivotRows = \Illuminate\Support\Facades\DB::table('event_member')->where('event_id', $event->id)->get();
    echo "Raw Pivot Rows for this UUID: " . count($pivotRows) . "\n";

    $allPivots = \Illuminate\Support\Facades\DB::table('event_member')->get();
    echo "Total Pivot rows in DB: " . count($allPivots) . "\n";
    if (count($allPivots) > 0) {
        echo "Sample event_id in pivot: " . $allPivots[0]->event_id . "\n";
    }
}
else {
    echo "No events found.\n";
}
