<?php

namespace App\Http\Controllers;

use App\Actions\Events\RegisterMemberForEvent;
use App\Models\Event;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $now = Carbon::now();

        // Sanitise filters; invalid enum values are simply ignored.
        $format = in_array($request->query('format'), ['event', 'workshop', 'course'], true)
            ? $request->query('format') : null;
        $level = in_array($request->query('level'), ['beginner', 'intermediate', 'advanced'], true)
            ? $request->query('level') : null;
        $specialty = $request->filled('specialty') ? (string) $request->query('specialty') : null;
        $q = trim((string) $request->query('q', ''));

        $applyFilters = function ($query) use ($format, $level, $specialty, $q) {
            if ($format) {
                $query->where('format', $format);
            }
            if ($level) {
                $query->where('level', $level);
            }
            if ($specialty) {
                $query->where('specialty', $specialty);
            }
            if ($q !== '') {
                $query->where(function ($sub) use ($q) {
                    $sub->where('title_en', 'like', "%{$q}%")
                        ->orWhere('title_ar', 'like', "%{$q}%")
                        ->orWhere('description_en', 'like', "%{$q}%")
                        ->orWhere('description_ar', 'like', "%{$q}%");
                });
            }

            return $query;
        };

        // Happening Now: started but not ended.
        $happeningNow = $applyFilters(
            Event::where('is_active', true)
                ->where('start_date', '<=', $now)
                ->where(fn ($sub) => $sub->where('end_date', '>=', $now))
        )->orderBy('start_date', 'asc')->get();

        // Upcoming: starts in the future.
        $upcomingEvents = $applyFilters(
            Event::where('is_active', true)
                ->where('start_date', '>', $now)
        )->orderBy('start_date', 'asc')->get();

        // Past: ended (or single-moment events whose start has passed).
        $pastEvents = $applyFilters(
            Event::where('is_active', true)
                ->where(function ($sub) use ($now) {
                    $sub->where('end_date', '<', $now)
                        ->orWhere(fn ($q2) => $q2->whereNull('end_date')->where('start_date', '<', $now));
                })
        )->orderBy('start_date', 'desc')->take(6)->get();

        $specialties = Specialty::query()->where('is_active', true)->orderBy('sort_order')->get();
        $activeFilters = array_filter(compact('format', 'level', 'specialty', 'q'), fn ($v) => $v !== null && $v !== '');

        return view('events.index', compact(
            'happeningNow', 'upcomingEvents', 'pastEvents',
            'specialties', 'format', 'level', 'specialty', 'q', 'activeFilters',
        ));
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

    public function register(Request $request, $slug, RegisterMemberForEvent $register)
    {
        $event = Event::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $member = Auth::guard('member')->user();

        $register->execute($event, $member);

        $message = app()->getLocale() == 'ar' ? 'تم تسجيلك بنجاح في الفعالية!' : 'You have successfully registered for the event!';

        return redirect()->back()->with('success', $message);
    }
}
