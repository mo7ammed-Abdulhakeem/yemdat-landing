<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\PasswordResetOtpEmail;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function verifyIdentity(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $member = Member::where('email', $request->email)->first();

        if (!$member) {
            return back()->withErrors(['email' => app()->getLocale() == 'ar' ? 'عذراً لا يوجد حساب بهذا البريد الإلكتروني.' : 'We could not find an account with that email.']);
        }

        // Generate OTP and store in member table
        $otp = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        $member->otp_code = Hash::make($otp);
        $member->otp_expires_at = now()->addMinutes(10);
        $member->save();

        // Dispatch Password Reset OTP Email
        try {
            Mail::to($member->email)->send(new PasswordResetOtpEmail([
                'name' => $member->full_name,
                'otp' => $otp,
            ]));
        }
        catch (\Exception $e) {
            Log::error('Password Reset OTP Email failed: ' . $e->getMessage());
        }

        session(['reset_member_id' => $member->id]);
        return redirect()->route('password.verify.otp');
    }

    public function showOtpVerificationForm()
    {
        if (!session()->has('reset_member_id')) {
            return redirect()->route('password.request');
        }
        return view('auth.verify-password-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|string|size:6']);

        $memberId = session('reset_member_id');
        if (!$memberId)
            return redirect()->route('password.request');

        $member = Member::find($memberId);
        if (!$member)
            return redirect()->route('password.request');

        if (!$member->otp_code || !Hash::check($request->otp, $member->otp_code) || $member->otp_expires_at->isPast()) {
            return back()->withErrors(['otp' => app()->getLocale() == 'ar' ? 'رمز التحقق غير صحيح أو منتهي الصلاحية.' : 'Invalid or expired OTP code.']);
        }

        // Clean slate matching
        $member->otp_code = null;
        $member->otp_expires_at = null;
        $member->save();

        // Pass context to the original direct claim/password route
        session()->forget('reset_member_id');
        session(['claim_member_id' => $member->id, 'is_password_reset' => true]);
        return redirect()->route('claim.profile.set-password', ['token' => 'reset-password']);
    }
}
