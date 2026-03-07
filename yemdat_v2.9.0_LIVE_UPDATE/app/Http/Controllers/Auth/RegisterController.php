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
    public function showRegistrationForm()
    {
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

        $member = Member::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone_code' => $validated['phone_code'],
            'phone_number' => $validated['phone_number'],
            'country' => $validated['country'],
            'gender' => $validated['gender'],
            'education_level' => $validated['education_level'],
            'specialty' => $validated['specialty'],
            'specialty_other' => $validated['specialty_other'] ?? null,
            'membership_type' => $validated['membership_type'],
        ]);

        // 2. Generate OTP and store in member table
        $otp = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        $member->otp_code = Hash::make($otp);
        $member->otp_expires_at = now()->addMinutes(10);
        $member->save();

        // 3. Dispatch OTP Email
        try {
            \Illuminate\Support\Facades\Mail::to($member->email)->send(new \App\Mail\SignupOtpEmail([
                'name' => $member->full_name,
                'otp' => $otp,
            ]));
        }
        catch (\Exception $e) {
            \Log::error('OTP Email failed during registration: ' . $e->getMessage());
        }

        // 4. Redirect to OTP screen
        session(['verify_member_id' => $member->id]);
        return redirect()->route('verification.notice');
    }
}
