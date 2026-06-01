<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Member;

class ProfileController extends Controller
{
    public function show()
    {
        $member = Auth::guard('member')->user();

        if (!$member) {
            // Technically shouldn't happen, but gracefully handle if user has no member profile yet
            return redirect()->route('home')->withErrors(['error' => 'You do not have a registered community profile.']);
        }

        // Load registered events once, then split into upcoming vs past for the dashboard.
        $events = $member->events()->orderBy('start_date', 'desc')->get();
        $now = now();
        [$upcomingEvents, $pastEvents] = $events->partition(
            fn ($event) => ($event->end_date ?? $event->start_date) >= $now
        );
        // Soonest upcoming event first; past events stay newest-first.
        $upcomingEvents = $upcomingEvents->sortBy('start_date')->values();
        $pastEvents = $pastEvents->values();
        $nextEvent = $upcomingEvents->first();

        // Map of event_id => valid certificate, for the "Download Certificate" buttons.
        $certificatesByEvent = $member->certificates()
            ->whereNull('revoked_at')
            ->get()
            ->keyBy('event_id');

        $completion = $member->profileCompletion();

        return view('profile.show', compact(
            'member',
            'upcomingEvents',
            'pastEvents',
            'nextEvent',
            'certificatesByEvent',
            'completion'
        ));
    }

    public function edit()
    {
        $member = Auth::guard('member')->user();

        if (!$member) {
            return redirect()->route('home')->withErrors(['error' => 'You do not have a registered community profile.']);
        }

        return view('profile.edit', compact('member'));
    }

    public function update(Request $request)
    {
        $member = Auth::guard('member')->user();

        if (!$member) {
            return redirect()->route('home')->withErrors(['error' => 'You do not have a registered community profile.']);
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_code' => 'required|string|max:10',
            'phone_number' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female',
            'education_level' => 'nullable|string|max:255',
            // `members.specialty` is NOT NULL (and required at registration + in the form),
            // so it must be required here too — otherwise an absent value writes null and 500s.
            'specialty' => 'required|string|max:255',
            'specialty_other' => 'nullable|string|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'bio' => 'nullable|string',
            'password' => ['nullable', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $updateData = [
            'full_name' => $validated['full_name'],
            'phone_code' => $validated['phone_code'],
            'phone_number' => $validated['phone_number'],
            'country' => $validated['country'],
            'gender' => $validated['gender'],
            'education_level' => $validated['education_level'] ?? null,
            'specialty' => $validated['specialty'] ?? null,
            'specialty_other' => $validated['specialty_other'] ?? null,
            'linkedin_url' => $validated['linkedin_url'] ?? null,
            'bio' => $validated['bio'] ?? null,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        // Update the member directly
        $member->update($updateData);

        return redirect()->route('profile.show')->with('success', app()->getLocale() == 'ar' ? 'تم تحديث البيانات بنجاح.' : 'Profile updated successfully.');
    }

    public function updateEmailPreference(Request $request)
    {
        $member = Auth::guard('member')->user();

        if ($request->input('action') === 'resubscribe') {
            $member->update(['unsubscribed_at' => null]);
        }

        return back()->with('success', app()->getLocale() == 'ar'
            ? 'تم تحديث تفضيلات البريد الإلكتروني.'
            : 'Email preferences updated.');
    }
}
