<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TestEmailController extends Controller
{
    public function index()
    {
        if (!app()->environment('local')) {
            abort(404);
        }

        $emails = Cache::get('test_emails_log', []);
        return view('test-emails.index', compact('emails'));
    }

    public function show($id)
    {
        if (!app()->environment('local')) {
            abort(404);
        }

        $emails = Cache::get('test_emails_log', []);
        $email = collect($emails)->firstWhere('id', $id);

        if (!is_array($email) || !isset($email['content'])) {
            abort(404);
        }

        return response($email['content'])->header('Content-Type', 'text/html; charset=UTF-8');
    }

    public function clear()
    {
        if (!app()->environment('local')) {
            abort(404);
        }

        Cache::forget('test_emails_log');
        return back()->with('success', 'Email log cleared.');
    }
}
