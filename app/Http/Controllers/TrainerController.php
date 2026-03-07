<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\TrainerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TrainerController extends Controller
{
    public function create()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('trainer', compact('settings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'country' => 'required|string|max:100',
            'linkedin_url' => 'nullable|url|max:255',
            'program_type' => 'required|string|in:workshop,course',
            'duration_days' => 'required|integer|min:1',
            'duration_hours' => 'required|integer|min:1',
            'agenda' => 'required|string',
            'agreed_to_free_provision' => 'required|boolean',
        ]);

        $trainerRequest = TrainerRequest::create($validated);

        // Format the new data into a cohesive string for the legacy {help_topic} email template placeholder
        $programStr = ucfirst($validated['program_type']) . " (" . $validated['duration_days'] . " Days, " . $validated['duration_hours'] . " Hours)";

        // Send auto-reply to the applicant
        try {
            \Illuminate\Support\Facades\Log::info('Attempting to send Applicant Auto-Reply to: ' . $validated['email']);
            \Illuminate\Support\Facades\Mail::to($validated['email'])->send(new \App\Mail\TrainerAutoReplyEmail([
                'name' => $trainerRequest->name,
                'email' => $trainerRequest->email,
                'phone_number' => $trainerRequest->phone_number,
                'country' => $trainerRequest->country,
                'help_topic' => $programStr,
            ]));
        }
        catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('CRITICAL: Failed to send trainer auto-reply: ' . $e->getMessage() . ' at Line: ' . $e->getLine());
        }

        // Send notification to Admin
        $adminEmail = Setting::where('key', 'admin_email')->value('value') ?? config('mail.from.address');
        if ($adminEmail) {
            try {
                \Illuminate\Support\Facades\Log::info('Attempting to send Admin Alert to: ' . $adminEmail);
                \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\TrainerRequestNotification($trainerRequest));
            }
            catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('CRITICAL: Failed to send trainer admin alert: ' . $e->getMessage() . ' at Line: ' . $e->getLine());
            }
        }

        return back()->with('success', app()->getLocale() == 'ar' ? 'تم إرسال طلبك بنجاح! سنتواصل معك قريباً.' : 'Your request has been submitted successfully! We will contact you soon.');
    }
}
