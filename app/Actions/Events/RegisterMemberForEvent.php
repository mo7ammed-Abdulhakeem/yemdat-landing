<?php

namespace App\Actions\Events;

use App\Mail\EventConfirmationEmail;
use App\Models\EmailTemplate;
use App\Models\Event;
use App\Models\Member;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Registers a member for an event and (if enabled) sends the confirmation email.
 *
 * Domain logic lives here so the public controller and future admin/Filament
 * tooling share a single, tested code path.
 */
class RegisterMemberForEvent
{
    /**
     * @return bool true if the member was newly registered, false if already registered
     */
    public function execute(Event $event, Member $member): bool
    {
        if ($event->members()->where('member_id', $member->id)->exists()) {
            return false;
        }

        $event->members()->attach($member->id);
        $this->sendConfirmation($event, $member);

        return true;
    }

    private function sendConfirmation(Event $event, Member $member): void
    {
        if (! EmailTemplate::isActiveFor('EventConfirmationEmail')) {
            return;
        }

        $locale = app()->getLocale();

        try {
            Mail::to($member->email)->queue(new EventConfirmationEmail([
                'name' => $member->full_name,
                'event_title' => $locale === 'ar' ? $event->title_ar : $event->title_en,
                'start_date' => $event->start_date->format('l, F j, Y g:i A'),
                'location' => $event->location,
                'join_url_text' => $event->join_url
                    ? '<a href="'.$event->join_url.'">'.$event->join_url.'</a>'
                    : '',
            ], $event));
        } catch (\Throwable $e) {
            Log::error('Event Confirmation Email failed: '.$e->getMessage());
        }
    }
}
