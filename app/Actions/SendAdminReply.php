<?php

namespace App\Actions;

use App\Mail\AdminReplyEmail;
use App\Models\EmailReply;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

/**
 * Sends a free-form admin reply to a repliable record (Contact / TrainerRequest),
 * records it in the reply history, and flips the record to "replied".
 *
 * Single, tested code path shared by the Filament reply actions.
 *
 * @param  array{from_name:string, from_email:string, subject:string, body:string}  $data
 */
class SendAdminReply
{
    public function execute(Model $replyable, array $data, ?User $issuer = null): EmailReply
    {
        $to = $replyable->email ?? null;

        if (! $to) {
            throw new RuntimeException('This record has no email address to reply to.');
        }

        // Send first — if delivery throws, we don't record a reply or change status.
        Mail::to($to)->send(new AdminReplyEmail(
            $data['from_name'],
            $data['from_email'],
            $data['subject'],
            $data['body'],
        ));

        /** @var EmailReply $reply */
        $reply = $replyable->replies()->create([
            'from_name' => $data['from_name'],
            'from_email' => $data['from_email'],
            'subject' => $data['subject'],
            'body' => $data['body'],
            'sent_by' => $issuer?->id,
        ]);

        $replyable->markReplied();

        return $reply;
    }
}
