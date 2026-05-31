<?php

namespace Tests\Feature;

use App\Jobs\ProcessBroadcastJob;
use App\Mail\BroadcastEmail;
use App\Models\EmailBroadcast;
use App\Models\EmailBroadcastRecipient;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Tests\TestCase;

class BroadcastJobTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        putenv('BROADCAST_DAILY_LIMIT');
        unset($_ENV['BROADCAST_DAILY_LIMIT'], $_SERVER['BROADCAST_DAILY_LIMIT']);
        parent::tearDown();
    }

    private function setDailyLimit(int $limit): void
    {
        putenv("BROADCAST_DAILY_LIMIT={$limit}");
        $_ENV['BROADCAST_DAILY_LIMIT'] = (string) $limit;
        $_SERVER['BROADCAST_DAILY_LIMIT'] = (string) $limit;
    }

    private function seedBroadcastWithRecipients(int $count): EmailBroadcast
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        $broadcast = EmailBroadcast::create([
            'subject_en' => 'Hi', 'body_en' => '<p>Hi</p>',
            'audience_type' => 'all_members', 'language' => 'en',
            'status' => 'sending', 'sent_by' => $admin->id, 'total_recipients' => $count,
        ]);

        for ($i = 1; $i <= $count; $i++) {
            $member = Member::create([
                'full_name' => "Member {$i}", 'email' => "m{$i}@example.com",
                'phone_code' => '+967', 'phone_number' => '70000000'.$i,
                'country' => 'Yemen', 'specialty' => 'Data', 'membership_type' => 'member',
            ]);
            EmailBroadcastRecipient::create([
                'broadcast_id' => $broadcast->id,
                'member_id' => $member->id,
                'email' => $member->email,
                'tracking_token' => (string) Str::uuid(),
                'open_count' => 0,
            ]);
        }

        return $broadcast;
    }

    public function test_job_sends_only_the_daily_limit_then_reschedules(): void
    {
        Mail::fake();
        Queue::fake();
        $this->setDailyLimit(2);

        $broadcast = $this->seedBroadcastWithRecipients(3);

        (new ProcessBroadcastJob($broadcast->id))->handle();

        Mail::assertSent(BroadcastEmail::class, 2);
        $this->assertEquals(2, $broadcast->recipients()->whereNotNull('sent_at')->count());
        $this->assertEquals(1, $broadcast->recipients()->whereNull('sent_at')->count());
        $this->assertSame('sending', $broadcast->fresh()->status);
        Queue::assertPushed(ProcessBroadcastJob::class); // rescheduled for the next day
    }

    public function test_job_marks_broadcast_sent_when_all_recipients_done(): void
    {
        Mail::fake();
        Queue::fake();
        $this->setDailyLimit(10);

        $broadcast = $this->seedBroadcastWithRecipients(3);

        (new ProcessBroadcastJob($broadcast->id))->handle();

        Mail::assertSent(BroadcastEmail::class, 3);
        $this->assertEquals(3, $broadcast->recipients()->whereNotNull('sent_at')->count());
        $this->assertSame('sent', $broadcast->fresh()->status);
        Queue::assertNotPushed(ProcessBroadcastJob::class);
    }
}
