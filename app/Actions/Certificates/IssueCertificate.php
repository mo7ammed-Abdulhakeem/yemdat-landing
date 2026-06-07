<?php

namespace App\Actions\Certificates;

use App\Models\Certificate;
use App\Models\EmailTemplate;
use App\Models\Event;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Issues a Certificate of Completion for a (member, event) pair.
 *
 * A single, tested code path shared by the Filament Event "Attendees"
 * relation manager (single + bulk actions). Issuance is gated on the member
 * being registered for the event AND marked completed.
 */
class IssueCertificate
{
    public function execute(Event $event, Member $member, ?User $issuer = null, bool $autoEmail = true): Certificate
    {
        $registration = $event->members()->where('member_id', $member->id)->first();

        if (! $registration) {
            throw new RuntimeException('This member is not registered for the event.');
        }

        if (! $registration->pivot->completed_at) {
            throw new RuntimeException('Mark the member as completed before issuing a certificate.');
        }

        $certificate = Certificate::firstOrCreate(
            ['member_id' => $member->id, 'event_id' => $event->id],
            ['type' => 'completion', 'issued_by' => $issuer?->id],
        );

        // Auto-email the member only for a freshly issued certificate, and only
        // when the template is active (toggling it off disables auto-send).
        // Bulk issuance passes $autoEmail = false so we don't fire dozens of
        // synchronous emails (mail is sync) in one request on shared hosting.
        // Mail failures must never block issuance.
        if ($autoEmail && $certificate->wasRecentlyCreated && EmailTemplate::isActiveFor('CertificateIssuedEmail')) {
            try {
                app(SendCertificateEmail::class)->execute($certificate);
            } catch (\Throwable $e) {
                Log::error('Auto-send certificate email failed: '.$e->getMessage());
            }
        }

        return $certificate;
    }
}
