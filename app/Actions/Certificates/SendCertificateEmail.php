<?php

namespace App\Actions\Certificates;

use App\Mail\CertificateIssuedEmail;
use App\Models\Certificate;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

/**
 * Emails a certificate (PDF attached) to its owning member and stamps emailed_at.
 *
 * Single, tested code path shared by the manual Filament action, the bulk
 * action, and the auto-send on issue (IssueCertificate).
 */
class SendCertificateEmail
{
    public function execute(Certificate $certificate, ?string $locale = null): void
    {
        $certificate->loadMissing('member', 'event');

        $member = $certificate->member;

        if (! $member || ! $member->email) {
            throw new RuntimeException('This certificate has no member email to send to.');
        }

        $locale ??= app()->getLocale();
        $event = $certificate->event;
        $eventTitle = $locale === 'ar'
            ? ($event?->title_ar ?? '')
            : ($event?->title_en ?? '');

        $placeholders = [
            'name' => $member->full_name,
            'event_title' => $eventTitle,
            'serial' => $certificate->serial,
            'issued_date' => optional($certificate->issued_at)->translatedFormat('F j, Y') ?? '',
            'verify_url' => route('certificates.verify', $certificate->serial),
        ];

        Mail::to($member->email)->send(
            new CertificateIssuedEmail($placeholders, $certificate, $locale)
        );

        $certificate->forceFill(['emailed_at' => now()])->save();
    }
}
