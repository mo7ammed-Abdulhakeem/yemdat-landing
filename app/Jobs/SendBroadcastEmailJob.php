<?php

namespace App\Jobs;

use App\Mail\BroadcastEmail;
use App\Models\EmailBroadcastRecipient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBroadcastEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 60;

    public function __construct(public EmailBroadcastRecipient $recipient)
    {
    }

    public function handle(): void
    {
        // Ensure broadcast relationship is loaded
        $this->recipient->loadMissing('broadcast');

        try {
            // send() here is intentional — the job IS the queue unit; no double-queuing
            Mail::to($this->recipient->email)->send(new BroadcastEmail($this->recipient));
        } catch (\Throwable $e) {
            Log::error('Broadcast email failed for recipient ' . $this->recipient->id . ': ' . $e->getMessage());
            throw $e; // Let the queue retry
        }
    }
}
