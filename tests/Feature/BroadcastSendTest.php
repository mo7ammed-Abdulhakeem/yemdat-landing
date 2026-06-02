<?php

namespace Tests\Feature;

use App\Actions\Broadcasts\SendBroadcast;
use App\Jobs\ProcessBroadcastJob;
use App\Models\EmailBroadcast;
use App\Models\Event;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * Covers the shared broadcast-dispatch logic in App\Actions\Broadcasts\SendBroadcast, which
 * powers the Filament EmailBroadcasts "Send" / "Send to new" actions. (Status/permission gating
 * lives in the Filament action's visible() condition, not in this action.)
 */
class BroadcastSendTest extends TestCase
{
    use RefreshDatabase;

    private int $phoneSeq = 0;

    private function member(string $email, ?string $unsubscribedAt = null): Member
    {
        // phone_number is unique in the members table — keep each test member distinct.
        return Member::create([
            'full_name' => 'Member '.$email,
            'email' => $email,
            'phone_code' => '+967',
            'phone_number' => '70000'.str_pad((string) ++$this->phoneSeq, 4, '0', STR_PAD_LEFT),
            'country' => 'Yemen',
            'specialty' => 'Data',
            'membership_type' => 'member',
            'unsubscribed_at' => $unsubscribedAt,
        ]);
    }

    private function draft(array $overrides = []): EmailBroadcast
    {
        $admin = User::factory()->create(['role' => 'super_admin']);

        return EmailBroadcast::create(array_merge([
            'subject_en' => 'Hello',
            'body_en' => '<p>Hello world</p>',
            'audience_type' => 'all_members',
            'language' => 'en',
            'status' => 'draft',
            'sent_by' => $admin->id,
        ], $overrides));
    }

    public function test_sending_to_all_members_excludes_unsubscribed_and_queues_job(): void
    {
        Queue::fake();
        $this->member('subscribed@example.com');
        $this->member('left@example.com', now()->toDateTimeString());
        $broadcast = $this->draft();

        $count = app(SendBroadcast::class)->send($broadcast);

        $this->assertSame(1, $count);
        $broadcast->refresh();
        $this->assertSame('sending', $broadcast->status);
        $this->assertEquals(1, $broadcast->total_recipients);
        $this->assertEquals(1, $broadcast->recipients()->count());
        $this->assertDatabaseHas('email_broadcast_recipients', ['email' => 'subscribed@example.com']);
        $this->assertDatabaseMissing('email_broadcast_recipients', ['email' => 'left@example.com']);
        Queue::assertPushed(ProcessBroadcastJob::class);
    }

    public function test_sending_event_broadcast_targets_only_registrants(): void
    {
        Queue::fake();
        $event = Event::create([
            'title_en' => 'Workshop', 'title_ar' => 'ورشة', 'slug' => 'workshop-x',
            'start_date' => now()->addWeek(), 'is_active' => true,
        ]);
        $registered = $this->member('registered@example.com');
        $this->member('outsider@example.com');
        $event->members()->attach($registered->id);

        $broadcast = $this->draft(['audience_type' => 'event_members', 'event_id' => $event->id]);

        $count = app(SendBroadcast::class)->send($broadcast);

        $this->assertSame(1, $count);
        $broadcast->refresh();
        $this->assertEquals(1, $broadcast->total_recipients);
        $this->assertDatabaseHas('email_broadcast_recipients', ['email' => 'registered@example.com']);
        $this->assertDatabaseMissing('email_broadcast_recipients', ['email' => 'outsider@example.com']);
        Queue::assertPushed(ProcessBroadcastJob::class);
    }

    public function test_sending_with_no_recipients_returns_zero_and_keeps_draft(): void
    {
        Queue::fake();
        $broadcast = $this->draft(); // all_members, but no members exist

        $count = app(SendBroadcast::class)->send($broadcast);

        $this->assertSame(0, $count);
        $this->assertSame('draft', $broadcast->fresh()->status);
        $this->assertDatabaseCount('email_broadcast_recipients', 0);
        Queue::assertNothingPushed();
    }

    public function test_send_to_new_targets_only_late_registrants(): void
    {
        Queue::fake();
        $event = Event::create([
            'title_en' => 'Workshop', 'title_ar' => 'ورشة', 'slug' => 'workshop-y',
            'start_date' => now()->addWeek(), 'is_active' => true,
        ]);
        $first = $this->member('first@example.com');
        $event->members()->attach($first->id);

        $broadcast = $this->draft(['audience_type' => 'event_members', 'event_id' => $event->id]);
        app(SendBroadcast::class)->send($broadcast); // initial send → 1 recipient

        // A new member registers for the event after the initial send.
        $late = $this->member('late@example.com');
        $event->members()->attach($late->id);

        $count = app(SendBroadcast::class)->sendToNew($broadcast->fresh());

        $this->assertSame(1, $count);
        $this->assertEquals(2, $broadcast->fresh()->total_recipients);
        $this->assertEquals(2, $broadcast->fresh()->recipients()->count());
        $this->assertDatabaseHas('email_broadcast_recipients', ['email' => 'late@example.com']);
        Queue::assertPushed(ProcessBroadcastJob::class, 2); // once for send, once for sendToNew
    }
}
