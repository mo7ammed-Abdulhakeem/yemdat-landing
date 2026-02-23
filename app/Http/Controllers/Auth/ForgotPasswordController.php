<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;

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
            'phone_number' => 'required|string'
        ]);

        $member = Member::where('email', $request->email)->first();

        if (!$member) {
            return back()->withErrors(['email' => app()->getLocale() == 'ar' ? 'عذراً لا يوجد حساب بهذا البريد الإلكتروني.' : 'We could not find an account with that email.']);
        }

        if ($member->phone_number !== $request->phone_number) {
            return back()->withInput($request->only('email'))->withErrors(['phone_number' => app()->getLocale() == 'ar' ? 'رقم الهاتف غير مطابق لسجلاتنا.' : 'Phone number does not match our records.']);
        }

        // Identity verified, simulate claim profile token flow for password reset
        session(['claim_member_id' => $member->id]);
        return redirect()->route('claim.profile.set-password', ['token' => 'reset-password']);
    }
}
