<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\TrainerRequest;
use Illuminate\Http\Request;

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

        // Send notification to Admin
        $adminEmail = Setting::where('key', 'admin_email')->value('value') ?? config('mail.from.address');
        if ($adminEmail) {
            \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\TrainerRequestNotification($trainerRequest));
        }

        return back()->with('success', app()->getLocale() == 'ar' ? 'تم إرسال طلبك بنجاح! سنتواصل معك قريباً.' : 'Your request has been submitted successfully! We will contact you soon.');
    }
}
