<?php

namespace App\Jobs;

use App\Mail\BroadcastEmail;
use App\Models\EmailBroadcast;
use App\Models\EmailBroadcastRecipient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class ProcessBroadcastJob implements ShouldQueue
{
    use Queueable;

    public int $timeout = 600; // 10 minutes max

    public function __construct(public int $broadcastId) {}

    public function handle(): void
    {
        $broadcast = EmailBroadcast::find($this->broadcastId);
        if (!$broadcast) {
            return;
        }

        $recipients = EmailBroadcastRecipient::where('broadcast_id', $this->broadcastId)->get();

        foreach ($recipients as $recipient) {
            try {
                Mail::to($recipient->email)->send(new BroadcastEmail($recipient));
            } catch (\Exception $e) {
                \Log::error("Broadcast email failed for recipient {$recipient->id}: " . $e->getMessage());
            }
        }
    }
}
