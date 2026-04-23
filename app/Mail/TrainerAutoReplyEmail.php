<?php

namespace App\Mail;

use App\Models\TrainerRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrainerAutoReplyEmail extends Mailable
{
    use Queueable, SerializesModels, DynamicEmailTrait;

    public $placeholders;

    public function __construct(array $placeholders = [])
    {
        $this->placeholders = $placeholders;
        $this->locale = app()->getLocale();
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $emailData = $this->parseDynamicTemplate($this->placeholders);

        $mail = $this->subject($emailData['subject'])
            ->view('emails.dynamic')
            ->with([
            'body' => $emailData['body'],
            'banner' => $emailData['banner'],
        ]);

        if (!empty($emailData['from_email'])) {
            $mail->from($emailData['from_email']);
        }

        return $mail;
    }
}
