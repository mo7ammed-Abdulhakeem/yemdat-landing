<?php

namespace Tests\Feature;

use App\Mail\AccountDeletionOtpEmail;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AccountDeletionTest extends TestCase
{
    use RefreshDatabase;

    private function member(): Member
    {
        return Member::create([
            'full_name' => 'Del Member',
            'email' => 'del@example.com',
            'phone_code' => '+967',
            'phone_number' => '700000000',
            'country' => 'Yemen',
            'specialty' => 'Data',
            'membership_type' => 'member',
            'password' => 'password',
        ]);
    }

    private function memberWithOtp(string $otp, ?\DateTimeInterface $expires = null): Member
    {
        $member = $this->member();
        $member->otp_code = Hash::make($otp);
        $member->otp_expires_at = $expires ?? now()->addMinutes(15);
        $member->save();

        return $member;
    }

    public function test_request_otp_emails_code_and_redirects_to_confirm(): void
    {
        Mail::fake();
        $member = $this->member();

        $response = $this->actingAs($member, 'member')
            ->from(route('profile.show'))
            ->post(route('profile.delete.request'));

        $response->assertRedirect(route('profile.delete.confirm'));
        $this->assertNotNull($member->fresh()->otp_code);
        Mail::assertSent(AccountDeletionOtpEmail::class);
    }

    public function test_valid_otp_deletes_account_and_logs_out(): void
    {
        $member = $this->memberWithOtp('123456');

        $response = $this->actingAs($member, 'member')
            ->from(route('profile.delete.confirm'))
            ->post(route('profile.delete.confirm.post'), ['otp' => '123456']);

        $response->assertRedirect('/');
        $this->assertDatabaseMissing('members', ['id' => $member->id]);
        $this->assertGuest('member');
    }

    public function test_invalid_otp_does_not_delete_account(): void
    {
        $member = $this->memberWithOtp('123456');

        $this->actingAs($member, 'member')
            ->from(route('profile.delete.confirm'))
            ->post(route('profile.delete.confirm.post'), ['otp' => '000000'])
            ->assertSessionHasErrors('otp');

        $this->assertDatabaseHas('members', ['id' => $member->id]);
    }

    public function test_expired_otp_does_not_delete_account(): void
    {
        $member = $this->memberWithOtp('123456', now()->subMinute());

        $this->actingAs($member, 'member')
            ->from(route('profile.delete.confirm'))
            ->post(route('profile.delete.confirm.post'), ['otp' => '123456'])
            ->assertSessionHasErrors('otp');

        $this->assertDatabaseHas('members', ['id' => $member->id]);
    }
}
