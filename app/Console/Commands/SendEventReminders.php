<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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
                    $eventName = app()->getLocale() == 'ar' ? $event->title_ar : $event->title_en;
                    $eventDate = $event->start_date->format('l, F j, Y g:i A');
                    $eventLocation = app()->getLocale() == 'ar' ? $event->location_ar : $event->location_en;

                    Mail::to($member->email)->queue(new EventReminderEmail([
                        'name' => $member->full_name,
                        'event_name' => $eventName,
                        'event_date' => $eventDate,
                        'event_location' => $eventLocation,
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
