<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Invites a newly-promoted trainer to set their /trainer panel password.
 * Template-based (admin-editable) via the email_templates table, keyed on this
 * class's basename — see EmailTemplateSeeder ('TrainerInviteEmail').
 *
 * Placeholders: {name} {set_password_url} {login_url}
 */
class TrainerInviteEmail extends Mailable
{
    use Queueable, SerializesModels, DynamicEmailTrait;

    public array $placeholders;

    public function __construct(array $placeholders = [])
    {
        $this->placeholders = $placeholders;
        $this->locale = app()->getLocale();
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

        if (! empty($emailData['from_email'])) {
            $mail->from($emailData['from_email']);
        }

        return $mail;
    }
}
