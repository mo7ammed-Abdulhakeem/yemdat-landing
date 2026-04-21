<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmailTemplate;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\EventReminderEmail;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends email reminders to registered members for events happening tomorrow.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = Carbon::tomorrow();

        $events = Event::with('members')
            ->where('is_active', true)
            ->whereDate('start_date', $tomorrow)
            ->get();

        $sentCount = 0;

        foreach ($events as $event) {
            foreach ($event->members as $member) {
                try {
                    if (!EmailTemplate::isActiveFor('EventReminderEmail')) {
                        continue;
                    }

                    $eventTitle    = app()->getLocale() == 'ar' ? $event->title_ar : $event->title_en;
                    $startDate     = $event->start_date->format('l, F j, Y g:i A');
                    $location      = app()->getLocale() == 'ar' ? ($event->location ?? '') : ($event->location ?? '');
                    $joinUrlText   = $event->join_url
                        ? '<a href="' . $event->join_url . '">' . $event->join_url . '</a>'
                        : '';

                    Mail::to($member->email)->queue(new EventReminderEmail([
                        'name'          => $member->full_name,
                        'event_title'   => $eventTitle,
                        'start_date'    => $startDate,
                        'location'      => $location,
                        'join_url_text' => $joinUrlText,
                    ]));

                    $sentCount++;
                }
                catch (\Exception $e) {
                    Log::error("Failed to send reminder for event {$event->id} to {$member->email}: " . $e->getMessage());
                }
            }
        }

        $this->info("Successfully dispatched {$sentCount} event reminders.");
    }
}
