<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('creator')->latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    public function show(Event $event)
    {
        $event->load('members.membershipTier');
        return view('admin.events.show', compact('event'));
    }

    public function exportMembers(Event $event)
    {
        $event->load('members.membershipTier');

        $filename = 'event_' . Str::slug($event->title_en) . '_registrations_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($event) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for proper Arabic/Unicode display in Excel
            fputs($file, "\xEF\xBB\xBF");

            // Headers
            fputcsv($file, [
                'Member Name',
                'Email',
                'Phone',
                'Tier',
                'Specialty',
                'Registration Date'
            ]);

            // Data rows
            foreach ($event->members as $member) {
                $phone = $member->phone_code . ' ' . $member->phone_number;
                $specialty = $member->specialty === 'Other' ? ($member->specialty_other ?? 'Other') : $member->specialty;

                fputcsv($file, [
                    $member->full_name,
                    $member->email,
                    $phone,
                    $member->membershipTier ? $member->membershipTier->name_en : 'Member',
                    $specialty,
                    $member->pivot->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'start_date' => 'required|date|after:today', // Future date only
            'end_date' => 'nullable|date|after:start_date',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024', // Max 1MB
            'lecturer_name_en' => 'required|string|max:255',
            'lecturer_name_ar' => 'required|string|max:255',
            'lecturer_title_en' => 'nullable|string|max:255',
            'lecturer_title_ar' => 'nullable|string|max:255',
            'lecturer_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024', // Max 1MB
            'lecturer_linkedin' => 'nullable|url|max:255',
            'join_url' => 'nullable|url|max:255',
            // 'is_active' => 'boolean', // Removed to avoid validation error on unchecked
        ]);

        // Generate Slug from English Title
        $slug = Str::slug($validated['title_en']);
        if (Event::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }
        $validated['slug'] = $slug;

        // Handle Event Image Upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        // Handle Lecturer Image Upload
        if ($request->hasFile('lecturer_image')) {
            $validated['lecturer_image'] = $request->file('lecturer_image')->store('lecturers', 'public');
        }

        // Add creator
        $validated['created_by'] = auth()->id();

        // Checkbox handling
        $validated['is_active'] = $request->has('is_active') ? true : false;

        Event::create($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'start_date' => 'required|date', // During update, we might not force 'after:today' if event already started, but usually safer to keep logic sane. Let's allow past dates on update in case of fixing typos on running events.
            'end_date' => 'nullable|date|after:start_date',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'lecturer_name_en' => 'required|string|max:255',
            'lecturer_name_ar' => 'required|string|max:255',
            'lecturer_title_en' => 'nullable|string|max:255',
            'lecturer_title_ar' => 'nullable|string|max:255',
            'lecturer_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'lecturer_linkedin' => 'nullable|url|max:255',
            'join_url' => 'nullable|url|max:255',
            // 'is_active' => 'boolean',
        ]);

        // Update Slug if title changed
        if ($event->title_en !== $validated['title_en']) {
            $slug = Str::slug($validated['title_en']);
            if (Event::where('slug', $slug)->where('id', '!=', $event->id)->exists()) {
                $slug = $slug . '-' . time();
            }
            $validated['slug'] = $slug;
        }

        // Handle Event Image Upload
        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        // Handle Lecturer Image Upload
        if ($request->hasFile('lecturer_image')) {
            if ($event->lecturer_image) {
                Storage::disk('public')->delete($event->lecturer_image);
            }
            $validated['lecturer_image'] = $request->file('lecturer_image')->store('lecturers', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }
        if ($event->lecturer_image) {
            Storage::disk('public')->delete($event->lecturer_image);
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
    }

    public function toggle(Event $event)
    {
        $event->update(['is_active' => !$event->is_active]);
        return back()->with('success', 'Event status updated.');
    }
}
