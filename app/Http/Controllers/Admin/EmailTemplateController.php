<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::orderBy('id')->get();
        return view('admin.emails.index', compact('templates'));
    }

    public function edit(EmailTemplate $email)
    {
        return view('admin.emails.edit', compact('email'));
    }

    public function update(Request $request, EmailTemplate $email)
    {
        $validated = $request->validate([
            'subject_en' => 'required|string|max:255',
            'subject_ar' => 'required|string|max:255',
            'body_en_b64' => 'nullable|string',
            'body_ar_b64' => 'nullable|string',
            'body_en' => 'nullable|string',
            'body_ar' => 'nullable|string',
            'banner_image' => 'nullable|image|max:2048',
            'from_email' => 'nullable|email|max:255',
        ]);

        if (empty($validated['body_en_b64']) && empty($validated['body_en'])) {
            return back()->withErrors(['body_en' => 'The English body is required.']);
        }
        if (empty($validated['body_ar_b64']) && empty($validated['body_ar'])) {
            return back()->withErrors(['body_ar' => 'The Arabic body is required.']);
        }

        if (!empty($validated['body_en_b64'])) {
            $validated['body_en'] = base64_decode($validated['body_en_b64']);
        }
        if (!empty($validated['body_ar_b64'])) {
            $validated['body_ar'] = base64_decode($validated['body_ar_b64']);
        }

        unset($validated['body_en_b64'], $validated['body_ar_b64']);

        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('email_banners', 'public');
            $validated['banner_image'] = $path;
        }

        $email->update($validated);

        return redirect()->route('admin.emails.index')->with('success', 'Email Template updated successfully.');
    }
}
