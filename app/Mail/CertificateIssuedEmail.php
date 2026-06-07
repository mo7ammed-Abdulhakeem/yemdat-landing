<?php

namespace App\Mail;

use App\Models\Certificate;
use App\Services\CertificatePdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Emails a member their certificate, with the rendered PDF attached.
 * Template-based (admin-editable) via the email_templates table, keyed on
 * this class's basename — see EmailTemplateSeeder ('CertificateIssuedEmail').
 *
 * Placeholders: {name} {event_title} {serial} {issued_date} {verify_url}
 */
class CertificateIssuedEmail extends Mailable
{
    use Queueable, SerializesModels, DynamicEmailTrait;

    public array $placeholders;
    public Certificate $certificate;

    public function __construct(array $placeholders, Certificate $certificate, ?string $locale = null)
    {
        $this->placeholders = $placeholders;
        $this->certificate = $certificate;
        // Setting the Mailable's $locale also drives translation/rendering locale.
        $this->locale = $locale ?: app()->getLocale();
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

        $mail->attachData(
            app(CertificatePdf::class)->render($this->certificate),
            'certificate-'.$this->certificate->serial.'.pdf',
            ['mime' => 'application/pdf'],
        );

        return $mail;
    }
}
