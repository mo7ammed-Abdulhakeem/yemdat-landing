<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Member;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use App\Models\MembershipTier;

class RegisterController extends Controller
{
    public function showRegistrationForm(Request $request)
    {
        if ($request->has('redirect')) {
            session(['url.intended' => $request->get('redirect')]);
        }
        $tiers = MembershipTier::where('is_active', true)->get();
        return view('auth.register', compact('tiers'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users|unique:members',
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone_code' => 'required|string|max:10',
            'phone_number' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female',
            'education_level' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'specialty_other' => 'nullable|string|max:255',
            'membership_type' => 'required|string|exists:membership_tiers,slug',
        ]);

        // 2. Generate OTP and store everything in session instead of database
        $otp = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

        $registrationData = $validated;
        $registrationData['otp_code'] = Hash::make($otp);
        $registrationData['otp_expires_at'] = now()->addMinutes(10);

        session(['pending_registration' => $registrationData]);

        // 3. Dispatch OTP Email
        try {
            \Illuminate\Support\Facades\Mail::to($validated['email'])->send(new \App\Mail\SignupOtpEmail([
                'name' => $validated['full_name'],
                'otp' => $otp,
            ]));
        }
        catch (\Exception $e) {
            \Log::error('OTP Email failed during registration: ' . $e->getMessage());
        }

        // 4. Redirect to OTP screen
        return redirect()->route('verification.notice');
    }
}
