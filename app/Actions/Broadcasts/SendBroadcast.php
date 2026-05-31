<?php

namespace App\Actions\Broadcasts;

use App\Jobs\ProcessBroadcastJob;
use App\Models\EmailBroadcast;
use App\Models\EmailBroadcastRecipient;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Shared broadcast-dispatch logic used by both the legacy BroadcastController and the
 * Filament admin panel: builds the recipient list, queues the chunked send job.
 *
 * Callers are responsible for status/permission guards and user messaging; these methods
 * return the number of recipients queued (0 = nothing to send).
 */
class SendBroadcast
{
    /**
     * Initial send of a draft broadcast to its full audience.
     */
    public function send(EmailBroadcast $broadcast): int
    {
        $members = $broadcast->audience_type === 'all_members'
            ? Member::whereNull('unsubscribed_at')->get()
            : ($broadcast->event ? $broadcast->event->members()->get() : collect());

        if ($members->isEmpty()) {
            return 0;
        }

        DB::table('email_broadcast_recipients')->insert($this->rows($broadcast, $members));

        $broadcast->update([
            'status' => 'sending',
            'total_recipients' => $members->count(),
            'sent_at' => now(),
        ]);

        ProcessBroadcastJob::dispatch($broadcast->id);

        return $members->count();
    }

    /**
     * Top-up: send to event registrants who joined after the broadcast was first sent.
     */
    public function sendToNew(EmailBroadcast $broadcast): int
    {
        $existingMemberIds = EmailBroadcastRecipient::where('broadcast_id', $broadcast->id)->pluck('member_id');
        $newMembers = $broadcast->event->members()->whereNotIn('members.id', $existingMemberIds)->get();

        if ($newMembers->isEmpty()) {
            return 0;
        }

        $lastId = EmailBroadcastRecipient::where('broadcast_id', $broadcast->id)->max('id') ?? 0;

        DB::table('email_broadcast_recipients')->insert($this->rows($broadcast, $newMembers));

        ProcessBroadcastJob::dispatch($broadcast->id, $lastId);
        $broadcast->increment('total_recipients', $newMembers->count());

        return $newMembers->count();
    }

    private function rows(EmailBroadcast $broadcast, $members): array
    {
        $now = now();

        return $members->map(fn ($member) => [
            'broadcast_id' => $broadcast->id,
            'member_id' => $member->id,
            'email' => $member->email,
            'tracking_token' => (string) Str::uuid(),
            'open_count' => 0,
            'created_at' => $now,
            'updated_at' => $now,
        ])->all();
    }
}
