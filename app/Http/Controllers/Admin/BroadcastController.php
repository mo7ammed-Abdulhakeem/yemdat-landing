<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Broadcasts\SendBroadcast;
use App\Http\Controllers\Controller;
use App\Jobs\SendBroadcastEmailJob;
use App\Models\EmailBroadcast;
use App\Models\EmailBroadcastRecipient;
use App\Models\Event;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BroadcastController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasPermission('broadcasts')) abort(403);

        $broadcasts = EmailBroadcast::with('creator', 'event')
            ->latest()
            ->paginate(15);

        return view('admin.broadcasts.index', compact('broadcasts'));
    }

    public function create()
    {
        if (!auth()->user()->hasPermission('broadcasts')) abort(403);

        $events = Event::orderBy('start_date', 'desc')->get();
        return view('admin.broadcasts.create', compact('events'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('broadcasts')) abort(403);
        $validated = $request->validate([
            'subject_en'    => 'required_if:language,en|nullable|string|max:255',
            'subject_ar'    => 'required_if:language,ar|nullable|string|max:255',
            'body_en'       => 'required_if:language,en|nullable|string',
            'body_ar'       => 'required_if:language,ar|nullable|string',
            'audience_type' => 'required|in:all_members,event_members',
            'event_id'      => 'required_if:audience_type,event_members|nullable|string|exists:events,id',
            'language'      => 'required|in:en,ar',
            'from_email'    => 'nullable|email|max:255',
            'from_name'     => 'nullable|string|max:255',
        ]);

        $validated['sent_by'] = auth()->id();
        $validated['status']  = 'draft';

        $broadcast = EmailBroadcast::create($validated);

        return redirect()->route('admin.broadcasts.show', $broadcast)
            ->with('success', 'Broadcast saved as draft.');
    }

    public function edit(EmailBroadcast $broadcast)
    {
        if (!auth()->user()->hasPermission('broadcasts')) abort(403);

        if ($broadcast->status !== 'draft') {
            return redirect()->route('admin.broadcasts.show', $broadcast)
                ->with('error', 'Only draft broadcasts can be edited.');
        }

        $events = Event::orderBy('start_date', 'desc')->get();
        return view('admin.broadcasts.edit', compact('broadcast', 'events'));
    }

    public function update(Request $request, EmailBroadcast $broadcast)
    {
        if (!auth()->user()->hasPermission('broadcasts')) abort(403);

        if ($broadcast->status !== 'draft') {
            return redirect()->route('admin.broadcasts.show', $broadcast)
                ->with('error', 'Only draft broadcasts can be edited.');
        }

        $validated = $request->validate([
            'subject_en'    => 'required_if:language,en|nullable|string|max:255',
            'subject_ar'    => 'required_if:language,ar|nullable|string|max:255',
            'body_en'       => 'required_if:language,en|nullable|string',
            'body_ar'       => 'required_if:language,ar|nullable|string',
            'audience_type' => 'required|in:all_members,event_members',
            'event_id'      => 'required_if:audience_type,event_members|nullable|string|exists:events,id',
            'language'      => 'required|in:en,ar',
            'from_email'    => 'nullable|email|max:255',
            'from_name'     => 'nullable|string|max:255',
        ]);

        $broadcast->update($validated);

        return redirect()->route('admin.broadcasts.show', $broadcast)
            ->with('success', 'Broadcast updated.');
    }

    public function destroy(EmailBroadcast $broadcast)
    {
        if (!auth()->user()->hasPermission('broadcasts')) abort(403);

        $broadcast->recipients()->delete();
        $broadcast->delete();

        return redirect()->route('admin.broadcasts.index')
            ->with('success', 'Broadcast deleted.');
    }

    public function show(EmailBroadcast $broadcast)
    {
        if (!auth()->user()->hasPermission('broadcasts')) abort(403);
        $broadcast->load('creator', 'event');
        $recipients = $broadcast->recipients()
            ->with('member')
            ->latest()
            ->paginate(25);

        $stats = [
            'total'        => $broadcast->total_recipients,
            'sent'         => $broadcast->recipients()->whereNotNull('sent_at')->count(),
            'opens'        => $broadcast->opens_count,
            'open_rate'    => $broadcast->open_rate,
            'unsubscribes' => $broadcast->unsubscribes_count,
        ];

        return view('admin.broadcasts.show', compact('broadcast', 'recipients', 'stats'));
    }

    public function send(Request $request, EmailBroadcast $broadcast, SendBroadcast $sender)
    {
        if (!auth()->user()->hasPermission('broadcasts')) abort(403);

        if ($broadcast->status !== 'draft') {
            return redirect()->route('admin.broadcasts.show', $broadcast)
                ->with('error', 'This broadcast has already been sent.');
        }

        $count = $sender->send($broadcast);

        if ($count === 0) {
            return redirect()->route('admin.broadcasts.show', $broadcast)
                ->with('error', 'No recipients found for this broadcast.');
        }

        return redirect()->route('admin.broadcasts.show', $broadcast)
            ->with('success', 'Broadcast queued for ' . $count . ' recipients.');
    }

    public function sendToNew(EmailBroadcast $broadcast, SendBroadcast $sender)
    {
        if (!auth()->user()->hasPermission('broadcasts')) abort(403);

        if ($broadcast->status !== 'sent') {
            return redirect()->route('admin.broadcasts.show', $broadcast)
                ->with('error', 'Top-up is only available for broadcasts that have finished sending.');
        }

        if ($broadcast->audience_type !== 'event_members' || !$broadcast->event) {
            return redirect()->route('admin.broadcasts.show', $broadcast)
                ->with('error', 'Top-up is only available for event broadcasts.');
        }

        $count = $sender->sendToNew($broadcast);

        if ($count === 0) {
            return redirect()->route('admin.broadcasts.show', $broadcast)
                ->with('error', 'No new registrants to send to.');
        }

        return redirect()->route('admin.broadcasts.show', $broadcast)
            ->with('success', 'Sending to ' . $count . ' new registrant(s).');
    }
}
