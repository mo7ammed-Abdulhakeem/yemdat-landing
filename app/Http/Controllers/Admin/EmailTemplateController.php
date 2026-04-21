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
            'subject_en'  => 'required|string|max:255',
            'subject_ar'  => 'required|string|max:255',
            'body_en'     => 'required|string',
            'body_ar'     => 'required|string',
            'banner_image'=> 'nullable|image|max:2048',
            'from_email'  => 'nullable|email|max:255',
            'is_active'   => 'nullable|boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('email_banners', 'public');
            $validated['banner_image'] = $path;
        }

        $email->update($validated);

        return redirect()->route('admin.emails.index')->with('success', 'Email Template updated successfully.');
    }

    public function toggle(EmailTemplate $email)
    {
        $email->update(['is_active' => !$email->is_active]);

        $state = $email->is_active ? 'enabled' : 'disabled';
        return back()->with('success', "{$email->mailable_class} {$state}.");
    }
}
