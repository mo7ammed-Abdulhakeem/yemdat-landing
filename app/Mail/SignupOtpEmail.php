<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SignupOtpEmail extends Mailable
{
    use Queueable, SerializesModels, DynamicEmailTrait;

    public $placeholders;

    public function __construct(array $placeholders = [])
    {
        $this->placeholders = $placeholders;
    }

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
