<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // Happening Now: Started but not ended
        $happeningNow = Event::where('is_active', true)
            ->where('start_date', '<=', $now)
            ->where(function ($q) use ($now) {
            // If end_date is present, it must be in future. 
            // If end_date is null, assume it's a short event that counts as 'past' immediately after start? 
            // Or maybe 'happening' for the day? Let's assume strict end_date or +2 hours if null? 
            // For simplicity: If end_date is null, it's NOT happening now (it's past).
            $q->where('end_date', '>=', $now);
        })
            ->orderBy('start_date', 'asc') // Earliest started first
            ->get();

        // Upcoming: Starts in future
        $upcomingEvents = Event::where('is_active', true)
            ->where('start_date', '>', $now)
            ->orderBy('start_date', 'asc')
            ->get();

        // Past: Ended
        $pastEvents = Event::where('is_active', true)
            ->where(function ($q) use ($now) {
            $q->where('end_date', '<', $now)
                ->orWhere(function ($sub) use ($now) {
                $sub->whereNull('end_date')
                    ->where('start_date', '<', $now);
            }
            );
        })
            ->orderBy('start_date', 'desc')
            ->take(6)
            ->get();

        return view('events.index', compact('happeningNow', 'upcomingEvents', 'pastEvents'));
    }

    public function show($slug)
    {
        $event = Event::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('events.show', compact('event'));
    }
}
