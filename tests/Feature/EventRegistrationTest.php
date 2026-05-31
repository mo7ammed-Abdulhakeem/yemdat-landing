<?php

namespace Tests\Feature;

use App\Mail\EventConfirmationEmail;
use App\Models\Event;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EventRegistrationTest extends TestCase
{
    use RefreshDatabase;

    private function makeMember(): Member
    {
        return Member::create([
            'full_name' => 'Test Member',
            'email' => 'member@example.com',
            'phone_code' => '+967',
            'phone_number' => '700000000',
            'country' => 'Yemen',
            'specialty' => 'Data Analysis',
            'membership_type' => 'member',
            'password' => 'password',
        ]);
    }

    private function makeEvent(array $overrides = []): Event
    {
        return Event::create(array_merge([
            'title_en' => 'Intro to Data',
            'title_ar' => 'مقدمة في البيانات',
            'slug' => 'intro-to-data',
            'start_date' => now()->addWeek(),
            'end_date' => now()->addWeek()->addHours(2),
            'location' => "Sana'a, Yemen",
            'is_active' => true,
        ], $overrides));
    }

    public function test_member_can_register_for_an_event(): void
    {
        Mail::fake();
        $member = $this->makeMember();
        $event = $this->makeEvent();

        $response = $this->actingAs($member, 'member')
            ->from(route('events.show', $event->slug))
            ->post(route('events.register', $event->slug));

        $response->assertRedirect(route('events.show', $event->slug));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('event_member', [
            'event_id' => $event->id,
            'member_id' => $member->id,
        ]);
    }

    public function test_confirmation_email_includes_the_event_location(): void
    {
        Mail::fake();
        $member = $this->makeMember();
        $event = $this->makeEvent(['location' => 'Aden, Yemen']);

        $this->actingAs($member, 'member')
            ->from(route('events.show', $event->slug))
            ->post(route('events.register', $event->slug));

        Mail::assertQueued(
            EventConfirmationEmail::class,
            fn (EventConfirmationEmail $mail) => ($mail->placeholders['location'] ?? null) === $event->location,
        );
    }

    public function test_member_is_not_registered_twice(): void
    {
        Mail::fake();
        $member = $this->makeMember();
        $event = $this->makeEvent();

        $register = fn () => $this->actingAs($member, 'member')
            ->from(route('events.show', $event->slug))
            ->post(route('events.register', $event->slug));

        $register();
        $register();

        $this->assertSame(1, $member->fresh()->events()->count());
        Mail::assertQueued(EventConfirmationEmail::class, 1);
    }
}
