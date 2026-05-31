<?php

namespace Tests\Feature;

use App\Models\EmailBroadcast;
use App\Models\EmailBroadcastRecipient;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class BroadcastTrackingTest extends TestCase
{
    use RefreshDatabase;

    private function recipient(array $overrides = []): EmailBroadcastRecipient
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $member = Member::create([
            'full_name' => 'Track Me', 'email' => 'track@example.com',
            'phone_code' => '+967', 'phone_number' => '700000000',
            'country' => 'Yemen', 'specialty' => 'Data', 'membership_type' => 'member',
        ]);
        $broadcast = EmailBroadcast::create([
            'subject_en' => 'Hi', 'body_en' => '<p>Hi</p>',
            'audience_type' => 'all_members', 'language' => 'en',
            'status' => 'sent', 'sent_by' => $admin->id,
        ]);

        return EmailBroadcastRecipient::create(array_merge([
            'broadcast_id' => $broadcast->id,
            'member_id' => $member->id,
            'email' => $member->email,
            'tracking_token' => (string) Str::uuid(),
            'open_count' => 0,
        ], $overrides));
    }

    public function test_open_pixel_records_an_open(): void
    {
        $recipient = $this->recipient();

        $response = $this->get(route('track.open', $recipient->tracking_token));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'image/gif');
        $recipient->refresh();
        $this->assertEquals(1, $recipient->open_count);
        $this->assertNotNull($recipient->opened_at);
    }

    public function test_open_pixel_with_unknown_token_still_returns_a_gif(): void
    {
        $this->get(route('track.open', 'unknown-token'))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/gif');
    }

    public function test_unsubscribe_marks_recipient_and_member(): void
    {
        $recipient = $this->recipient();

        $this->post(route('unsubscribe.confirm', $recipient->tracking_token))->assertOk();

        $recipient->refresh();
        $this->assertNotNull($recipient->unsubscribed_at);
        $this->assertNotNull($recipient->member->fresh()->unsubscribed_at);
    }

    public function test_resubscribe_clears_member_unsubscribe(): void
    {
        $recipient = $this->recipient(['unsubscribed_at' => now()]);
        $recipient->member->update(['unsubscribed_at' => now()]);

        $this->post(route('resubscribe.confirm', $recipient->tracking_token))->assertOk();

        $this->assertNull($recipient->member->fresh()->unsubscribed_at);
    }
}
