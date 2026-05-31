<?php

namespace Tests\Feature;

use App\Mail\PasswordResetOtpEmail;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    private function member(array $overrides = []): Member
    {
        return Member::create(array_merge([
            'full_name' => 'Reset Member',
            'email' => 'reset@example.com',
            'phone_code' => '+967',
            'phone_number' => '700000000',
            'country' => 'Yemen',
            'specialty' => 'Data',
            'membership_type' => 'member',
            'password' => 'old-password',
        ], $overrides));
    }

    public function test_unknown_email_is_rejected_and_sends_no_mail(): void
    {
        Mail::fake();

        $this->from(route('password.request'))
            ->post(route('password.verify'), ['email' => 'nobody@example.com'])
            ->assertRedirect(route('password.request'))
            ->assertSessionHasErrors('email');

        Mail::assertNothingSent();
    }

    public function test_known_member_receives_otp_and_advances_to_verify_screen(): void
    {
        Mail::fake();
        $member = $this->member();

        $response = $this->from(route('password.request'))
            ->post(route('password.verify'), ['email' => $member->email]);

        $response->assertRedirect(route('password.verify.otp'));
        $response->assertSessionHas('reset_member_id', $member->id);
        $this->assertNotNull($member->fresh()->otp_code);
        Mail::assertSent(PasswordResetOtpEmail::class);
    }

    public function test_valid_otp_advances_to_set_password_and_clears_otp(): void
    {
        $member = $this->member();
        $member->otp_code = Hash::make('123456');
        $member->otp_expires_at = now()->addMinutes(10);
        $member->save();

        $response = $this->withSession(['reset_member_id' => $member->id])
            ->from(route('password.verify.otp'))
            ->post(route('password.verify.otp.post'), ['otp' => '123456']);

        $response->assertRedirect(route('claim.profile.set-password', ['token' => 'reset-password']));
        $response->assertSessionHas('claim_member_id', $member->id);
        $response->assertSessionHas('is_password_reset', true);
        $this->assertNull($member->fresh()->otp_code);
    }

    public function test_invalid_otp_is_rejected_and_otp_is_kept(): void
    {
        $member = $this->member();
        $member->otp_code = Hash::make('123456');
        $member->otp_expires_at = now()->addMinutes(10);
        $member->save();

        $this->withSession(['reset_member_id' => $member->id])
            ->from(route('password.verify.otp'))
            ->post(route('password.verify.otp.post'), ['otp' => '999999'])
            ->assertSessionHasErrors('otp');

        $this->assertNotNull($member->fresh()->otp_code);
    }
}
