<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * A free-form reply an admin composes in the panel (contact / trainer request).
 * Not template-based: the subject/body/from come straight from the compose form.
 * Reuses the branded emails.dynamic layout so it looks on-brand.
 */
class AdminReplyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $fromName,
        public string $fromEmail,
        public string $subjectLine,
        public string $bodyHtml,
    ) {}

    public function build()
    {
        // Send from the authorized mailbox (the admin's chosen name is kept as the
        // display name) so SPF/DKIM stay aligned on shared hosting — sending as an
        // arbitrary typed address gets bounced or spam-filed. The typed address is
        // where the recipient's reply should go, so it becomes the Reply-To.
        return $this->subject($this->subjectLine)
            ->from(config('mail.from.address'), $this->fromName)
            ->replyTo($this->fromEmail, $this->fromName)
            ->view('emails.dynamic')
            ->with([
                'body' => $this->bodyHtml,
                'banner' => null,
            ]);
    }
}
