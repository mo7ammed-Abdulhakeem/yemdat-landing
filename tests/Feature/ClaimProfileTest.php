<?php

namespace Tests\Feature;

use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ClaimProfileTest extends TestCase
{
    use RefreshDatabase;

    /** An imported member that has no password yet (profile not claimed). */
    private function unclaimedMember(): Member
    {
        return Member::create([
            'full_name' => 'Imported Member',
            'email' => 'imported@example.com',
            'phone_code' => '+967',
            'phone_number' => '700111222',
            'country' => 'Yemen',
            'specialty' => 'Data',
            'membership_type' => 'member',
        ]);
    }

    public function test_verify_email_rejects_unknown_address(): void
    {
        $this->from(route('claim.profile'))
            ->post(route('claim.profile.verify'), ['email' => 'nobody@example.com'])
            ->assertSessionHasErrors('email');
    }

    public function test_verify_email_rejects_already_claimed_profile(): void
    {
        $member = $this->unclaimedMember();
        $member->update(['password' => Hash::make('already-set')]);

        $this->from(route('claim.profile'))
            ->post(route('claim.profile.verify'), ['email' => $member->email])
            ->assertSessionHasErrors('email');
    }

    public function test_verify_email_for_unclaimed_member_advances_to_set_password(): void
    {
        $member = $this->unclaimedMember();

        $response = $this->from(route('claim.profile'))
            ->post(route('claim.profile.verify'), ['email' => $member->email]);

        $response->assertRedirect(route('claim.profile.set-password', ['token' => 'verify-phone']));
        $response->assertSessionHas('claim_member_id', $member->id);
    }

    public function test_claim_requires_matching_phone_number(): void
    {
        $member = $this->unclaimedMember();

        $this->withSession(['claim_member_id' => $member->id])
            ->from(route('claim.profile.set-password', ['token' => 'verify-phone']))
            ->post(route('claim.profile.set-password.post'), [
                'phone_number' => '999999999', // wrong
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
            ])
            ->assertSessionHasErrors('phone_number');

        $this->assertNull($member->fresh()->password);
    }

    public function test_claim_sets_password_and_logs_in_with_correct_phone(): void
    {
        $member = $this->unclaimedMember();

        $response = $this->withSession(['claim_member_id' => $member->id])
            ->from(route('claim.profile.set-password', ['token' => 'verify-phone']))
            ->post(route('claim.profile.set-password.post'), [
                'phone_number' => '700111222', // matches on file
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
            ]);

        $response->assertRedirect(route('profile.show'));
        $this->assertTrue(Hash::check('new-password-123', $member->fresh()->password));
        $this->assertTrue(Auth::guard('member')->check());
        $response->assertSessionMissing('claim_member_id');
    }

    public function test_password_reset_path_skips_phone_check(): void
    {
        $member = $this->unclaimedMember();
        $member->update(['password' => Hash::make('old-password')]);

        $response = $this->withSession([
            'claim_member_id' => $member->id,
            'is_password_reset' => true,
        ])
            ->from(route('claim.profile.set-password', ['token' => 'reset-password']))
            ->post(route('claim.profile.set-password.post'), [
                'password' => 'reset-password-123',
                'password_confirmation' => 'reset-password-123',
            ]);

        $response->assertRedirect(route('profile.show'));
        $this->assertTrue(Hash::check('reset-password-123', $member->fresh()->password));
        $this->assertTrue(Auth::guard('member')->check());
    }

    public function test_set_password_without_session_redirects(): void
    {
        $this->post(route('claim.profile.set-password.post'), [
            'password' => 'whatever-123',
            'password_confirmation' => 'whatever-123',
        ])->assertRedirect(route('claim.profile'));
    }
}
