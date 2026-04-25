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

    public function __construct(public int $broadcastId, public int $afterRecipientId = 0) {}

    private function dailyLimit(): int
    {
        return (int) env('BROADCAST_DAILY_LIMIT', 80);
    }

    public function handle(): void
    {
        $broadcast = EmailBroadcast::find($this->broadcastId);
        if (!$broadcast) {
            return;
        }

        $recipients = EmailBroadcastRecipient::where('broadcast_id', $this->broadcastId)
            ->when($this->afterRecipientId > 0, fn($q) => $q->where('id', '>', $this->afterRecipientId))
            ->whereNull('sent_at')
            ->limit($this->dailyLimit())
            ->get();

        foreach ($recipients as $recipient) {
            try {
                Mail::to($recipient->email)->send(new BroadcastEmail($recipient));
                $recipient->update(['sent_at' => now()]);
            } catch (\Exception $e) {
                \Log::error("Broadcast email failed for recipient {$recipient->id}: " . $e->getMessage());
            }
        }

        $hasMore = EmailBroadcastRecipient::where('broadcast_id', $this->broadcastId)
            ->when($this->afterRecipientId > 0, fn($q) => $q->where('id', '>', $this->afterRecipientId))
            ->whereNull('sent_at')
            ->exists();

        if ($hasMore) {
            static::dispatch($this->broadcastId, $this->afterRecipientId)->delay(now()->addDay());
        } elseif ($this->afterRecipientId === 0) {
            $broadcast->update(['status' => 'sent']);
        }
    }
}
