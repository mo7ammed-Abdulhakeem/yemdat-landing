<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Show Member Analytics Dashboard
     */
    public function members()
    {
        return view('admin.analytics.members');
    }

    /**
     * Show Event Analytics Dashboard
     */
    public function events()
    {
        return view('admin.analytics.events');
    }

    /**
     * API Endpoint: Aggregated Member JSON Data
     */
    public function memberData(Request $request)
    {
        $query = Member::query();

        // Apply Slicer Filters
        if ($request->filled('date_range')) {
            $range = $request->input('date_range');
            if ($range === '7days') {
                $query->where('created_at', '>=', now()->subDays(7));
            }
            elseif ($range === '30days') {
                $query->where('created_at', '>=', now()->subDays(30));
            }
            elseif ($range === 'this_year') {
                $query->whereYear('created_at', now()->year);
            }
        }

        if ($request->filled('country')) {
            $query->where('country', $request->input('country'));
        }

        if ($request->filled('specialty')) {
            $query->where('specialty', $request->input('specialty'));
        }

        // 1. Growth Trend (Daily for now)
        $growthTrend = (clone $query)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 2. Gender Distribution
        $genderDist = (clone $query)
            ->selectRaw('COALESCE(gender, "Not Specified") as gender, COUNT(*) as count')
            ->groupBy('gender')
            ->get();

        // 3. Top Specialties
        $topSpecialties = (clone $query)
            ->selectRaw('COALESCE(specialty, "Not Specified") as specialty, COUNT(*) as count')
            ->groupBy('specialty')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // 4. Education Breakdown
        $educationDist = (clone $query)
            ->selectRaw('COALESCE(education_level, "Not Specified") as education_level, COUNT(*) as count')
            ->groupBy('education_level')
            ->get();

        // 5. Geographic Spread
        $geoSpread = (clone $query)
            ->selectRaw('COALESCE(country, "Not Specified") as country, COUNT(*) as count')
            ->groupBy('country')
            ->orderByDesc('count')
            ->get();

        // Filter Options for Slicers
        $availableCountries = Member::select('country')->distinct()->whereNotNull('country')->pluck('country');
        $availableSpecialties = Member::select('specialty')->distinct()->whereNotNull('specialty')->pluck('specialty');

        return response()->json([
            'growthTrend' => $growthTrend,
            'genderDist' => $genderDist,
            'topSpecialties' => $topSpecialties,
            'educationDist' => $educationDist,
            'geoSpread' => $geoSpread,
            'filters' => [
                'countries' => $availableCountries,
                'specialties' => $availableSpecialties,
            ]
        ]);
    }

    /**
     * API Endpoint: Aggregated Event JSON Data
     */
    public function eventData(Request $request)
    {
        $query = clone Event::query();

        // Apply Slicer Filters
        if ($request->filled('event_date')) {
            $range = $request->input('event_date');
            if ($range === 'upcoming') {
                $query->where('start_date', '>=', now());
            }
            elseif ($range === 'past') {
                $query->where('start_date', '<', now());
            }
            elseif ($range === 'this_month') {
                $query->whereMonth('start_date', now()->month)->whereYear('start_date', now()->year);
            }
        }

        if ($request->filled('event_id')) {
            $query->where('id', $request->input('event_id'));
        }

        if ($request->filled('location_type')) {
            $query->where('location', 'like', '%' . $request->input('location_type') . '%');
        }

        if ($request->filled('lecturer')) {
            $query->where('lecturer_name_en', $request->input('lecturer'));
        }

        // 1. Event Attendance (Registrations count per event)
        $attendance = (clone $query)
            ->withCount('members')
            ->orderByDesc('members_count')
            ->limit(10)
            ->get()
            ->map(function ($e) {
            return ['title' => $e->title_en, 'count' => $e->members_count];
        });

        // 2. Top Lecturers
        $topLecturers = (clone $query)
            ->selectRaw('lecturer_name_en as lecturer, COUNT(*) as count')
            ->groupBy('lecturer_name_en')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // 3. Events Timeline (by Month)
        $timeline = (clone $query)
            ->selectRaw('DATE_FORMAT(start_date, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // 4. Active vs Past
        // Based on start_date, not just is_active flag.
        $activeVsPast = [
            ['status' => 'Upcoming/Live', 'count' => (clone $query)->where('start_date', '>=', now())->count()],
            ['status' => 'Past', 'count' => (clone $query)->where('start_date', '<', now())->count()]
        ];

        // 5. Registration Pace
        // This requires joining Pivot, which is complex for a simple query.
        // Let's get the daily registration counts for the events currently matched in $query.
        $eventIds = (clone $query)->pluck('id');
        $regPace = DB::table('event_member')
            ->whereIn('event_id', $eventIds)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Filter Options for Slicers
        $availableLecturers = Event::select('lecturer_name_en')->distinct()->whereNotNull('lecturer_name_en')->pluck('lecturer_name_en');
        $availableEvents = Event::select('id', 'title_en')->orderByDesc('start_date')->get();

        return response()->json([
            'attendance' => $attendance,
            'topLecturers' => $topLecturers,
            'timeline' => $timeline,
            'activeVsPast' => $activeVsPast,
            'regPace' => $regPace,
            'filters' => [
                'lecturers' => $availableLecturers,
                'eventsList' => $availableEvents
            ]
        ]);
    }
}
