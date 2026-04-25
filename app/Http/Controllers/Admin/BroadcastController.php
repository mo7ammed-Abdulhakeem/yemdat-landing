<?php

namespace App\Http\Controllers\Admin;

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
        $broadcasts = EmailBroadcast::with('creator', 'event')
            ->latest()
            ->paginate(15);

        return view('admin.broadcasts.index', compact('broadcasts'));
    }

    public function create()
    {
        $events = Event::orderBy('start_date', 'desc')->get();
        return view('admin.broadcasts.create', compact('events'));
    }

    public function store(Request $request)
    {
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

    public function show(EmailBroadcast $broadcast)
    {
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

    public function send(Request $request, EmailBroadcast $broadcast)
    {
        if ($broadcast->status !== 'draft') {
            return redirect()->route('admin.broadcasts.show', $broadcast)
                ->with('error', 'This broadcast has already been sent.');
        }

        // Collect recipients based on audience type
        if ($broadcast->audience_type === 'all_members') {
            $members = Member::whereNull('unsubscribed_at')->get();
        } else {
            // Event-specific: ignore unsubscribed_at
            $event   = $broadcast->event;
            $members = $event ? $event->members()->get() : collect();
        }

        if ($members->isEmpty()) {
            return redirect()->route('admin.broadcasts.show', $broadcast)
                ->with('error', 'No recipients found for this broadcast.');
        }

        // Bulk insert recipient rows
        $now = now();
        $rows = $members->map(fn($member) => [
            'broadcast_id'   => $broadcast->id,
            'member_id'      => $member->id,
            'email'          => $member->email,
            'tracking_token' => (string) Str::uuid(),
            'open_count'     => 0,
            'created_at'     => $now,
            'updated_at'     => $now,
        ])->all();

        DB::table('email_broadcast_recipients')->insert($rows);

        $broadcast->update([
            'status'           => 'sending',
            'total_recipients' => $members->count(),
            'sent_at'          => now(),
        ]);

        // Dispatch after updating status so the job's final 'sent' update wins
        \App\Jobs\ProcessBroadcastJob::dispatch($broadcast->id);

        return redirect()->route('admin.broadcasts.show', $broadcast)
            ->with('success', 'Broadcast queued for ' . $members->count() . ' recipients.');
    }

    public function sendToNew(EmailBroadcast $broadcast)
    {
        if ($broadcast->status !== 'sent') {
            return redirect()->route('admin.broadcasts.show', $broadcast)
                ->with('error', 'Top-up is only available for broadcasts that have finished sending.');
        }

        if ($broadcast->audience_type !== 'event_members' || !$broadcast->event) {
            return redirect()->route('admin.broadcasts.show', $broadcast)
                ->with('error', 'Top-up is only available for event broadcasts.');
        }

        $existingMemberIds = EmailBroadcastRecipient::where('broadcast_id', $broadcast->id)->pluck('member_id');
        $newMembers = $broadcast->event->members()->whereNotIn('members.id', $existingMemberIds)->get();

        if ($newMembers->isEmpty()) {
            return redirect()->route('admin.broadcasts.show', $broadcast)
                ->with('error', 'No new registrants to send to.');
        }

        $lastId = EmailBroadcastRecipient::where('broadcast_id', $broadcast->id)->max('id') ?? 0;
        $now = now();
        DB::table('email_broadcast_recipients')->insert(
            $newMembers->map(fn($m) => [
                'broadcast_id'   => $broadcast->id,
                'member_id'      => $m->id,
                'email'          => $m->email,
                'tracking_token' => (string) Str::uuid(),
                'open_count'     => 0,
                'created_at'     => $now,
                'updated_at'     => $now,
            ])->all()
        );

        \App\Jobs\ProcessBroadcastJob::dispatch($broadcast->id, $lastId);
        $broadcast->increment('total_recipients', $newMembers->count());

        return redirect()->route('admin.broadcasts.show', $broadcast)
            ->with('success', 'Sending to ' . $newMembers->count() . ' new registrant(s).');
    }
}
