<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\AccountDeletionOtpEmail;
use Carbon\Carbon;

class AccountDeletionController extends Controller
{
    public function requestOtp(Request $request)
    {
        $member = Auth::guard('member')->user();

        // Generate a 6-digit OTP
        $otp = sprintf('%06d', mt_rand(100000, 999999));

        $member->otp_code = Hash::make($otp);
        $member->otp_expires_at = Carbon::now()->addMinutes(15);
        $member->save();

        Mail::to($member->email)->send(new AccountDeletionOtpEmail([
            'name' => $member->full_name,
            'otp'  => $otp,
        ]));

        return redirect()->route('profile.delete.confirm')->with('success', app()->getLocale() == 'ar' ? 'تم إرسال رمز التحقق إلى بريدك الإلكتروني.' : 'A verification code has been sent to your email.');
    }

    public function showConfirm()
    {
        return view('profile.delete-confirm');
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $member = Auth::guard('member')->user();

        if (!$member->otp_code || !$member->otp_expires_at || Carbon::now()->isAfter($member->otp_expires_at)) {
            return back()->withErrors(['otp' => app()->getLocale() == 'ar' ? 'لقد انتهت صلاحية الرمز، يرجى المحاولة مرة أخرى.' : 'The code has expired, please request a new one.']);
        }

        if (!Hash::check($request->otp, $member->otp_code)) {
            return back()->withErrors(['otp' => app()->getLocale() == 'ar' ? 'الرمز غير صحيح.' : 'The code is incorrect.']);
        }

        $member->delete();

        Auth::guard('member')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', app()->getLocale() == 'ar' ? 'تم حذف حسابك بنجاح.' : 'Your account has been successfully deleted.');
    }
}
