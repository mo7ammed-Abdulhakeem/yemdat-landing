<?php

namespace App\Mail;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventConfirmationEmail extends Mailable
{
    use Queueable, SerializesModels, DynamicEmailTrait;

    public $placeholders;
    public ?Event $event;

    public function __construct(array $placeholders = [], ?Event $event = null)
    {
        $this->placeholders = $placeholders;
        $this->event = $event;
    }

    public function build()
    {
        $emailData = $this->parseDynamicTemplate($this->placeholders);

        $mail = $this->subject($emailData['subject'])
            ->view('emails.dynamic')
            ->with([
                'body'   => $emailData['body'],
                'banner' => $emailData['banner'],
            ]);

        if (!empty($emailData['from_email'])) {
            $mail->from($emailData['from_email']);
        }

        if ($this->event) {
            $mail->attachData($this->buildIcs(), 'event.ics', [
                'mime' => 'text/calendar',
            ]);
        }

        return $mail;
    }

    private function buildIcs(): string
    {
        $start    = $this->event->start_date->utc()->format('Ymd\THis\Z');
        $end      = ($this->event->end_date ?? $this->event->start_date->copy()->addHour())->utc()->format('Ymd\THis\Z');
        $title    = addcslashes($this->event->title_en ?? '', ',;\\');
        $location = addcslashes($this->event->location ?? '', ',;\\');
        $url      = $this->event->join_url ?? '';
        $uid      = $this->event->id . '@yemdat.com';

        return implode("\r\n", [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Yemdat//EN',
            'METHOD:REQUEST',
            'BEGIN:VEVENT',
            "UID:{$uid}",
            "DTSTART:{$start}",
            "DTEND:{$end}",
            "SUMMARY:{$title}",
            "LOCATION:{$location}",
            "URL:{$url}",
            'END:VEVENT',
            'END:VCALENDAR',
            '',
        ]);
    }
}
