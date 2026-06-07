<?php

namespace Tests\Feature;

use App\Actions\Trainers\PromoteMemberToTrainer;
use App\Actions\Trainers\RevokeTrainer;
use App\Actions\Trainers\SendTrainerInvite;
use App\Mail\TrainerInviteEmail;
use App\Models\Event;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Tests\TestCase;

class TrainerPromotionTest extends TestCase
{
    use RefreshDatabase;

    private function makeMember(array $overrides = []): Member
    {
        static $i = 0;
        $i++;

        return Member::create(array_merge([
            'full_name' => 'Trainer Candidate '.$i,
            'email' => "trainer{$i}@example.com",
            'phone_code' => '+967',
            'phone_number' => '70000000'.$i,
            'country' => 'Yemen',
            'specialty' => 'data-analytics',
            'membership_type' => 'member',
            'password' => 'password',
        ], $overrides));
    }

    public function test_promoting_a_member_creates_a_linked_trainer_user_and_emails_invite(): void
    {
        Mail::fake();
        $member = $this->makeMember(['email' => 'promote.me@example.com']);

        $user = app(PromoteMemberToTrainer::class)->execute($member);

        $this->assertSame('trainer', $user->role);
        $this->assertSame('promote.me@example.com', $user->email);
        $this->assertSame($user->id, $member->fresh()->user_id);
        $this->assertTrue($member->fresh()->isTrainer());

        Mail::assertSent(TrainerInviteEmail::class, fn ($mail) => $mail->hasTo('promote.me@example.com'));
    }

    public function test_promotion_aborts_when_email_belongs_to_an_admin(): void
    {
        Mail::fake();
        User::create([
            'name' => 'Existing Admin',
            'email' => 'clash@example.com',
            'password' => 'password',
            'role' => 'admin',
        ]);
        $member = $this->makeMember(['email' => 'clash@example.com']);

        $this->expectException(RuntimeException::class);

        try {
            app(PromoteMemberToTrainer::class)->execute($member);
        } finally {
            $this->assertNull($member->fresh()->user_id);
            Mail::assertNothingSent();
        }
    }

    public function test_promoting_is_idempotent(): void
    {
        Mail::fake();
        $member = $this->makeMember();

        $first = app(PromoteMemberToTrainer::class)->execute($member);
        $second = app(PromoteMemberToTrainer::class)->execute($member->fresh());

        $this->assertSame($first->id, $second->id);
        $this->assertSame(1, User::where('role', 'trainer')->count());
    }

    public function test_resend_invite_emails_a_fresh_set_password_link(): void
    {
        Mail::fake();
        $user = User::create([
            'name' => 'Existing Trainer',
            'email' => 'resend@example.com',
            'password' => 'password',
            'role' => 'trainer',
        ]);

        app(SendTrainerInvite::class)->execute($user, 'Existing Trainer');

        Mail::assertSent(TrainerInviteEmail::class, fn ($mail) => $mail->hasTo('resend@example.com'));
    }

    public function test_revoke_does_not_delete_a_linked_admin_user(): void
    {
        $admin = User::create([
            'name' => 'Real Admin',
            'email' => 'realadmin@example.com',
            'password' => 'password',
            'role' => 'admin',
        ]);
        $member = $this->makeMember();
        $member->forceFill(['user_id' => $admin->id])->save();

        app(RevokeTrainer::class)->execute($member->fresh());

        // The admin account must survive; only the member link is cleared.
        $this->assertNotNull(User::find($admin->id));
        $this->assertNull($member->fresh()->user_id);
    }

    public function test_revoking_unlinks_member_and_deletes_trainer_user(): void
    {
        Mail::fake();
        $member = $this->makeMember();
        $user = app(PromoteMemberToTrainer::class)->execute($member);

        $event = Event::create([
            'title_en' => 'T', 'title_ar' => 'ت', 'slug' => 'rev-evt',
            'start_date' => now()->addWeek(), 'is_active' => true,
            'trainer_id' => $user->id,
        ]);

        app(RevokeTrainer::class)->execute($member->fresh());

        $this->assertNull($member->fresh()->user_id);
        $this->assertNull(User::find($user->id));
        // The event's trainer assignment is nulled by the foreign key.
        $this->assertNull($event->fresh()->trainer_id);
    }
}
