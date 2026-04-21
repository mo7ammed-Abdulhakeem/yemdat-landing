<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\EventConfirmationEmail;

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

        $similarEventsQuery = Event::where('is_active', true)
            ->where('id', '!=', $event->id)
            ->where('start_date', '>=', now());

        if (\Illuminate\Support\Facades\Auth::guard('member')->check()) {
            $memberId = \Illuminate\Support\Facades\Auth::guard('member')->id();
            $similarEventsQuery->whereDoesntHave('members', function ($q) use ($memberId) {
                $q->where('members.id', $memberId);
            });
        }

        $similarEvents = $similarEventsQuery->orderBy('start_date', 'asc')
            ->take(3)
            ->get();

        return view('events.show', compact('event', 'similarEvents'));
    }

    public function register(Request $request, $slug)
    {
        $event = Event::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $member = Auth::guard('member')->user();

        // Attach if not already registered
        if (!$event->members()->where('member_id', $member->id)->exists()) {
            $event->members()->attach($member->id);

            // Dispatch Event Confirmation Email
            try {
                if (!EmailTemplate::isActiveFor('EventConfirmationEmail')) {
                    // Template disabled by admin — skip email
                    $message = app()->getLocale() == 'ar' ? 'تم تسجيلك بنجاح في الفعالية!' : 'You have successfully registered for the event!';
                    return redirect()->back()->with('success', $message);
                }

                $eventName = app()->getLocale() == 'ar' ? $event->title_ar : $event->title_en;
                $eventDate = $event->start_date->format('l, F j, Y g:i A');
                $eventLocation = app()->getLocale() == 'ar' ? $event->location_ar : $event->location_en;

                Mail::to($member->email)->queue(new EventConfirmationEmail([
                    'name'          => $member->full_name,
                    'event_title'   => $eventName,
                    'start_date'    => $eventDate,
                    'location'      => $eventLocation,
                    'join_url_text' => $event->join_url
                        ? '<a href="' . $event->join_url . '">' . $event->join_url . '</a>'
                        : '',
                ], $event));
            }
            catch (\Exception $e) {
                Log::error('Event Confirmation Email failed: ' . $e->getMessage());
            }
        }

        $message = app()->getLocale() == 'ar' ? 'تم تسجيلك بنجاح في الفعالية!' : 'You have successfully registered for the event!';
        return redirect()->back()->with('success', $message);
    }
}
