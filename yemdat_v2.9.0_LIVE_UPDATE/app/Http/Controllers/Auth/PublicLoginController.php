<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PublicLoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.public-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('member')->attempt($credentials)) {
            $member = Auth::guard('member')->user();

            $request->session()->regenerate();

            return redirect()->route('profile.show');
        }

        return back()->withErrors([
            'email' => app()->getLocale() == 'ar' ? 'بيانات الاعتماد المقدمة لا تتطابق مع سجلاتنا.' : 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('member')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
