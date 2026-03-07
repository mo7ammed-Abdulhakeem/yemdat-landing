<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use App\Models\Setting;
use App\Mail\ContactUsAutoReplyEmail;
use App\Mail\ContactUsAdminAlert;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if (auth()->guard('member')->check()) {
            $validated['member_id'] = auth()->guard('member')->id();
        }

        Contact::create($validated);

        try {
            Mail::to($validated['email'])->send(new ContactUsAutoReplyEmail([
                'name' => $validated['name'],
            ]));
        }
        catch (\Exception $e) {
            \Log::error('Failed to send contact auto-reply: ' . $e->getMessage());
        }

        $adminEmail = Setting::where('key', 'admin_email')->value('value');
        if (!$adminEmail) {
            $adminEmail = config('mail.from.address');
        }

        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new ContactUsAdminAlert($validated));
            }
            catch (\Exception $e) {
                \Log::error('Failed to send contact admin alert: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Your message has been sent successfully!');
    }
}
