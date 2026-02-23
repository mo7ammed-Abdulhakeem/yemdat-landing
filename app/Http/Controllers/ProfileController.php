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

        return view('profile.show', compact('member'));
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
            'specialty' => 'nullable|string|max:255',
            'specialty_other' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            // Notice: 'email' is intentionally excluded from validation and update
        ]);

        // Update the member directly
        $member->update([
            'full_name' => $validated['full_name'],
            'phone_code' => $validated['phone_code'],
            'phone_number' => $validated['phone_number'],
            'country' => $validated['country'],
            'gender' => $validated['gender'],
            'education_level' => $validated['education_level'] ?? null,
            'specialty' => $validated['specialty'] ?? null,
            'specialty_other' => $validated['specialty_other'] ?? null,
            'bio' => $validated['bio'] ?? null,
        ]);

        return redirect()->route('profile.show')->with('success', app()->getLocale() == 'ar' ? 'تم تحديث البيانات بنجاح.' : 'Profile updated successfully.');
    }
}
