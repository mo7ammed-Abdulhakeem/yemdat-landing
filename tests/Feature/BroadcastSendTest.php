<?php

namespace Tests\Feature;

use App\Jobs\ProcessBroadcastJob;
use App\Models\EmailBroadcast;
use App\Models\Event;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class BroadcastSendTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'super_admin']);
    }

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

    private function draft(User $admin, array $overrides = []): EmailBroadcast
    {
        return EmailBroadcast::create(array_merge([
            'subject_en' => 'Hello',
            'body_en' => '<p>Hello world</p>',
            'audience_type' => 'all_members',
            'language' => 'en',
            'status' => 'draft',
            'sent_by' => $admin->id,
        ], $overrides));
    }

    public function test_admin_can_save_a_draft_broadcast(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->post(route('admin.broadcasts.store'), [
            'subject_en' => 'Newsletter',
            'body_en' => '<p>Hi</p>',
            'audience_type' => 'all_members',
            'language' => 'en',
        ]);

        $broadcast = EmailBroadcast::first();
        $this->assertNotNull($broadcast);
        $this->assertSame('draft', $broadcast->status);
        $response->assertRedirect(route('admin.broadcasts.show', $broadcast));
    }

    public function test_sending_to_all_members_excludes_unsubscribed_and_queues_job(): void
    {
        Queue::fake();
        $admin = $this->admin();
        $this->member('subscribed@example.com');
        $this->member('left@example.com', now()->toDateTimeString());
        $broadcast = $this->draft($admin);

        $response = $this->actingAs($admin)->post(route('admin.broadcasts.send', $broadcast));

        $response->assertRedirect(route('admin.broadcasts.show', $broadcast));
        $broadcast->refresh();
        $this->assertSame('sending', $broadcast->status);
        $this->assertEquals(1, $broadcast->total_recipients);
        $this->assertEquals(1, $broadcast->recipients()->count());
        $this->assertDatabaseMissing('email_broadcast_recipients', ['email' => 'left@example.com']);
        Queue::assertPushed(ProcessBroadcastJob::class);
    }

    public function test_sending_event_broadcast_targets_only_registrants(): void
    {
        Queue::fake();
        $admin = $this->admin();
        $event = Event::create([
            'title_en' => 'Workshop', 'title_ar' => 'ورشة', 'slug' => 'workshop-x',
            'start_date' => now()->addWeek(), 'is_active' => true,
        ]);
        $registered = $this->member('registered@example.com');
        $this->member('outsider@example.com');
        $event->members()->attach($registered->id);

        $broadcast = $this->draft($admin, ['audience_type' => 'event_members', 'event_id' => $event->id]);

        $this->actingAs($admin)->post(route('admin.broadcasts.send', $broadcast));

        $broadcast->refresh();
        $this->assertEquals(1, $broadcast->total_recipients);
        $this->assertDatabaseHas('email_broadcast_recipients', ['email' => 'registered@example.com']);
        $this->assertDatabaseMissing('email_broadcast_recipients', ['email' => 'outsider@example.com']);
    }

    public function test_cannot_send_a_non_draft_broadcast(): void
    {
        Queue::fake();
        $admin = $this->admin();
        $broadcast = $this->draft($admin, ['status' => 'sent']);

        $this->actingAs($admin)
            ->post(route('admin.broadcasts.send', $broadcast))
            ->assertSessionHas('error');

        Queue::assertNothingPushed();
    }

    public function test_sending_with_no_recipients_keeps_draft_and_errors(): void
    {
        Queue::fake();
        $admin = $this->admin();
        $broadcast = $this->draft($admin); // all_members, but no members exist

        $this->actingAs($admin)
            ->post(route('admin.broadcasts.send', $broadcast))
            ->assertSessionHas('error');

        $this->assertSame('draft', $broadcast->fresh()->status);
        Queue::assertNothingPushed();
    }
}
