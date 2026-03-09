<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class ClaimProfileController extends Controller
{
    public function showClaimForm()
    {
        return view('auth.claim-profile');
    }

    public function verifyEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $member = Member::where('email', $request->email)->first();

        if (!$member) {
            return back()->withErrors(['email' => 'We could not find an existing membership with that email. Please register as a new user.']);
        }

        // If the member already has a password, their profile is considered claimed.
        if ($member->password) {
            return back()->withErrors(['email' => 'This profile has already been claimed. Please go to the Login page.']);
        }

        // Normally we would email this signed URL.
        // For simplicity and immediate access if email server isn't perfect,
        // we'll require them to confirm their phone number as a 2FA step instead of email link.
        // Let's redirect them to a verification page where they must enter their registered phone number.

        session(['claim_member_id' => $member->id]);
        return redirect()->route('claim.profile.set-password', ['token' => 'verify-phone']);
    }

    public function showSetPasswordForm(Request $request, $token)
    {
        $memberId = session('claim_member_id');
        if (!$memberId) {
            $redirectRoute = $token === 'reset-password' ? 'password.request' : 'claim.profile';
            return redirect()->route($redirectRoute)->withErrors(['email' => app()->getLocale() == 'ar' ? 'انتهت الجلسة. يرجى المحاولة مرة أخرى.' : 'Session expired. Please try again.']);
        }

        $member = Member::findOrFail($memberId);
        $isPasswordReset = $token === 'reset-password';

        return view('auth.set-password', compact('member', 'isPasswordReset'));
    }

    public function setPassword(Request $request)
    {
        $memberId = session('claim_member_id');
        if (!$memberId) {
            return redirect()->route('claim.profile')->withErrors(['email' => 'Session expired. Please try again.']);
        }

        $member = Member::findOrFail($memberId);

        $isPasswordReset = session('is_password_reset', false);

        $rules = [
            'password' => ['required', 'confirmed', Password::defaults()],
        ];

        if (!$isPasswordReset) {
            $rules['phone_number'] = 'required|string';
        }

        $request->validate($rules);

        // Security check: Make sure the phone number matches what is on file (only if claiming profile)
        if (!$isPasswordReset && $request->phone_number !== $member->phone_number) {
            return back()->withErrors(['phone_number' => app()->getLocale() == 'ar' ? 'رقم الهاتف غير صحيح.' : 'Incorrect phone number.']);
        }

        // Set Password directly on the Member model
        $member->update([
            'password' => Hash::make($request->password)
        ]);

        // Clear session and login using member guard
        session()->forget('claim_member_id');
        session()->forget('is_password_reset');
        Auth::guard('member')->login($member);

        return redirect()->route('profile.show')->with('success', app()->getLocale() == 'ar' ? 'تم تعيين كلمة المرور بنجاح. أهلاً بك!' : 'Password set successfully. Welcome!');
    }
}
