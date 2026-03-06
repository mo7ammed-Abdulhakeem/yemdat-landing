<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use App\Mail\SignupOtpEmail;

class VerificationController extends Controller
{
    public function show()
    {
        if (!session()->has('verify_member_id')) {
            return redirect()->route('public.login');
        }
        return view('auth.verify-email');
    }

    public function verify(Request $request)
    {
        $request->validate(['otp' => 'required|string|size:6']);

        $memberId = session('verify_member_id');
        if (!$memberId)
            return redirect()->route('public.login');

        $member = Member::find($memberId);
        if (!$member)
            return redirect()->route('public.login');

        if (!$member->otp_code || !Hash::check($request->otp, $member->otp_code) || $member->otp_expires_at->isPast()) {
            return back()->withErrors(['otp' => app()->getLocale() == 'ar' ? 'رمز التحقق غير صحيح أو منتهي الصلاحية.' : 'Invalid or expired OTP code.']);
        }

        // Success
        $member->email_verified_at = now();
        $member->otp_code = null;
        $member->otp_expires_at = null;
        $member->save();

        session()->forget('verify_member_id');

        // Send Welcome Email
        try {
            Mail::to($member->email)->send(new WelcomeEmail([
                'name' => $member->full_name,
            ]));
        }
        catch (\Exception $e) {
            \Log::error('Welcome Email failed: ' . $e->getMessage());
        }

        // Log them in natively
        Auth::guard('member')->login($member);

        return redirect()->route('profile.show')->with('success', app()->getLocale() == 'ar' ? 'تم تأكيد الحساب بنجاح! نأخذك الآن إلى ملفك الشخصي لتحديث بياناتك.' : 'Email verified successfully! Taking you to your profile.');
    }

    public function resend()
    {
        $memberId = session('verify_member_id');
        if (!$memberId)
            return redirect()->route('public.login');

        $member = Member::find($memberId);
        if (!$member)
            return redirect()->route('public.login');

        $otp = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        $member->otp_code = Hash::make($otp);
        $member->otp_expires_at = now()->addMinutes(10);
        $member->save();

        try {
            Mail::to($member->email)->send(new SignupOtpEmail([
                'name' => $member->full_name,
                'otp' => $otp,
            ]));
        }
        catch (\Exception $e) {
            \Log::error('Resend OTP Email failed: ' . $e->getMessage());
        }

        return back()->with('success', app()->getLocale() == 'ar' ? 'تم إعادة إرسال رمز التحقق.' : 'A new OTP has been sent to your email.');
    }
}
