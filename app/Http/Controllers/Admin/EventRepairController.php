<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Event;

class EventRepairController extends Controller
{
    /**
     * Show the repair tool UI with any detected orphan groups.
     */
    public function index()
    {
        // Find distinct event_ids in event_member that DO NOT exist in events table
        $orphanedGroups = DB::table('event_member')
            ->select('event_id', DB::raw('COUNT(member_id) as total_members'), DB::raw('MIN(created_at) as earliest_reg'), DB::raw('MAX(created_at) as latest_reg'))
            ->whereNotIn('event_id', function ($query) {
            $query->select('id')->from('events');
        })
            ->groupBy('event_id')
            ->get();

        // Get all live events so the admin can select where to funnel them
        $liveEvents = Event::orderBy('start_date', 'desc')->get();

        return view('admin.events.repair', compact('orphanedGroups', 'liveEvents'));
    }

    /**
     * Commit the mapping of an orphaned UUID to a live Event UUID.
     */
    public function repair(Request $request)
    {
        $validated = $request->validate([
            'mappings' => 'required|array',
            'mappings.*' => 'nullable|string|exists:events,id', // Can map to valid event, or empty to ignore
        ]);

        $repairedCount = 0;

        foreach ($request->mappings as $orphanId => $liveEventId) {
            if (!empty($liveEventId)) {
                // Perform the mass overwrite
                DB::table('event_member')
                    ->where('event_id', $orphanId)
                    ->update(['event_id' => $liveEventId]);

                $repairedCount++;
            }
        }

        if ($repairedCount > 0) {
            return redirect()->route('admin.events.index')->with('success', "Successfully repaired {$repairedCount} orphaned registration group(s)!");
        }

        return back()->with('success', 'No changes were saved.');
    }
}
