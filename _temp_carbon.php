<?php
require __DIR__ . '/vendor/autoload.php';
$now = \Carbon\Carbon::parse('2026-03-02 04:53:00');
$start_date = \Carbon\Carbon::parse('2026-03-03 22:00:00');

echo 'isToday: ' . ($start_date->isToday() ? 'yes' : 'no') . "\n";
echo 'isTomorrow: ' . ($start_date->isTomorrow() ? 'yes' : 'no') . "\n";

$days = $now->copy()->startOfDay()->diffInDays($start_date->copy()->startOfDay());
echo "DiffDays logic: " . $days . "\n";
