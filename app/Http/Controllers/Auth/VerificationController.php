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
        if (!session()->has('pending_registration')) {
            return redirect()->route('public.login');
        }
        return view('auth.verify-email');
    }

    public function verify(Request $request)
    {
        $request->validate(['otp' => 'required|string|size:6']);

        $pendingData = session('pending_registration');
        if (!$pendingData) {
            return redirect()->route('public.login');
        }

        if (!isset($pendingData['otp_code']) || !Hash::check($request->otp, $pendingData['otp_code']) || $pendingData['otp_expires_at']->isPast()) {
            return back()->withErrors(['otp' => app()->getLocale() == 'ar' ? 'رمز التحقق غير صحيح أو منتهي الصلاحية.' : 'Invalid or expired OTP code.']);
        }

        // OTP Success - Now Create the Member Record in the Database
        $member = Member::create([
            'full_name' => $pendingData['full_name'],
            'email' => $pendingData['email'],
            'password' => Hash::make($pendingData['password']),
            'phone_code' => $pendingData['phone_code'],
            'phone_number' => $pendingData['phone_number'],
            'country' => $pendingData['country'],
            'gender' => $pendingData['gender'],
            'education_level' => $pendingData['education_level'],
            'specialty' => $pendingData['specialty'],
            'specialty_other' => $pendingData['specialty_other'] ?? null,
            'membership_type' => $pendingData['membership_type'],
            'email_verified_at' => now(),
            // Ensure auth-bound OTP markers are null natively upon creation
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        session()->forget('pending_registration');

        // Send Welcome Email
        try {
            Mail::to($member->email)->queue(new WelcomeEmail([
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
        $pendingData = session('pending_registration');
        if (!$pendingData) {
            return redirect()->route('public.login');
        }

        $otp = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        $pendingData['otp_code'] = Hash::make($otp);
        $pendingData['otp_expires_at'] = now()->addMinutes(10);

        session(['pending_registration' => $pendingData]);

        try {
            Mail::to($pendingData['email'])->queue(new SignupOtpEmail([
                'name' => $pendingData['full_name'],
                'otp' => $otp,
            ]));
        }
        catch (\Exception $e) {
            \Log::error('Resend OTP Email failed: ' . $e->getMessage());
        }

        return back()->with('success', app()->getLocale() == 'ar' ? 'تم إعادة إرسال رمز التحقق.' : 'A new OTP has been sent to your email.');
    }
}
