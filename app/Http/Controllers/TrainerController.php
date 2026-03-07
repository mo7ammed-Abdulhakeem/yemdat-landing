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
            'help_topic' => 'required|string',
        ]);

        $trainerRequest = TrainerRequest::create($validated);

        // Send auto-reply to the applicant
        try {
            \Illuminate\Support\Facades\Mail::to($validated['email'])->send(new \App\Mail\TrainerAutoReplyEmail($trainerRequest));
        }
        catch (\Exception $e) {
            \Log::error('Failed to send trainer auto-reply: ' . $e->getMessage());
        }

        // Send notification to Admin
        $adminEmail = Setting::where('key', 'admin_email')->value('value') ?? config('mail.from.address');
        if ($adminEmail) {
            try {
                \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\TrainerRequestNotification($trainerRequest));
            }
            catch (\Exception $e) {
                \Log::error('Failed to send trainer admin alert: ' . $e->getMessage());
            }
        }

        return back()->with('success', app()->getLocale() == 'ar' ? 'تم إرسال طلبك بنجاح! سنتواصل معك قريباً.' : 'Your request has been submitted successfully! We will contact you soon.');
    }
}
