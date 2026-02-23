<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Explicitly check for admin status if needed here, but the guard 'admin' should suffice 
        // if only admins are in the users table and members are in members. 
        // Let's enforce that:
        if (Auth::guard('admin')->attempt($credentials)) {
            $user = Auth::guard('admin')->user();

            // Only allow super admins (if you are differentiating between members and admins in the same table)
            // However, Yemdat uses two tables (`users` for admins, `members` for community) 
            // We just need to make sure this user is actually an admin by checking a property if one exists,
            // or simply the fact they are in the `users` table via the admin guard.
            if (isset($user->is_super_admin) && !$user->is_super_admin) {
                Auth::guard('admin')->logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'You do not have administrative privileges.',
                ]);
            }

            return redirect()->intended('/admincpanel/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admincpanel/login');
    }
}
