<?php

namespace App\Mail;

use App\Models\EmailBroadcastRecipient;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BroadcastEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public EmailBroadcastRecipient $recipient)
    {
    }

    public function envelope(): Envelope
    {
        $broadcast = $this->recipient->broadcast;
        $lang      = $broadcast->language;
        $subject   = $broadcast->{"subject_{$lang}"};

        $envelope = new Envelope(subject: $subject);

        if ($broadcast->from_email) {
            $envelope = new Envelope(
                from: new \Illuminate\Mail\Mailables\Address(
                    $broadcast->from_email,
                    $broadcast->from_name ?: config('mail.from.name')
                ),
                subject: $subject,
            );
        }

        return $envelope;
    }

    public function content(): Content
    {
        $broadcast = $this->recipient->broadcast;
        $lang      = $broadcast->language;
        $body      = $broadcast->{"body_{$lang}"};

        // Append tracking pixel (1×1 transparent GIF)
        $pixelUrl = route('track.open', $this->recipient->tracking_token);
        $body .= '<img src="' . $pixelUrl . '" width="1" height="1" alt="" '
               . 'style="display:none!important;width:1px!important;height:1px!important;border:0!important;" />';

        // Append unsubscribe footer
        $unsubUrl = route('unsubscribe', $this->recipient->tracking_token);
        $unsubLabel = $lang === 'ar' ? 'إلغاء الاشتراك من الرسائل البريدية' : 'Unsubscribe from future emails';
        $body .= '<div style="text-align:center;margin-top:24px;padding-top:16px;border-top:1px solid #e5e7eb;">'
               . '<p style="font-size:12px;color:#6b7280;">'
               . '<a href="' . $unsubUrl . '" style="color:#9ca3af;text-decoration:underline;">' . $unsubLabel . '</a>'
               . '</p></div>';

        return new Content(
            view: 'emails.broadcast',
            with: [
                'body'     => $body,
                'language' => $lang,
            ],
        );
    }
}
