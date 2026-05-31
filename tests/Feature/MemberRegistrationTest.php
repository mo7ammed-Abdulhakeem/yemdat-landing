<?php

namespace Tests\Feature;

use App\Mail\SignupOtpEmail;
use App\Models\Member;
use App\Models\MembershipTier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MemberRegistrationTest extends TestCase
{
    use RefreshDatabase;

    private function tier(string $slug = 'member'): MembershipTier
    {
        return MembershipTier::create([
            'slug' => $slug,
            'name_en' => 'Member',
            'name_ar' => 'عضو',
            'description_en' => 'Standard membership',
            'description_ar' => 'عضوية عادية',
            'is_active' => true,
        ]);
    }

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'full_name' => 'New Member',
            'email' => 'new@example.com',
            'password' => 'password1234',
            'password_confirmation' => 'password1234',
            'phone_code' => '+967',
            'phone_number' => '700123456',
            'country' => 'Yemen',
            'gender' => 'Male',
            'education_level' => 'Bachelor',
            'specialty' => 'Data Science',
            'membership_type' => 'member',
        ], $overrides);
    }

    private function pendingRegistration(array $overrides = []): array
    {
        return array_merge([
            'full_name' => 'New Member',
            'email' => 'new@example.com',
            'password' => 'password1234',
            'phone_code' => '+967',
            'phone_number' => '700123456',
            'country' => 'Yemen',
            'gender' => 'Male',
            'education_level' => 'Bachelor',
            'specialty' => 'Data Science',
            'specialty_other' => null,
            'membership_type' => 'member',
            'otp_code' => Hash::make('123456'),
            'otp_expires_at' => now()->addMinutes(10),
        ], $overrides);
    }

    public function test_registration_stashes_pending_session_and_sends_otp_without_creating_member(): void
    {
        Mail::fake();
        $this->tier();

        $response = $this->from(route('register'))
            ->post(route('register.post'), $this->validPayload());

        $response->assertRedirect(route('verification.notice'));
        $response->assertSessionHas('pending_registration');
        $this->assertSame(0, Member::count(), 'Member must not exist until the OTP is verified');
        Mail::assertSent(SignupOtpEmail::class);
    }

    public function test_registration_validates_input(): void
    {
        Mail::fake();
        $this->tier();

        $response = $this->from(route('register'))->post(route('register.post'), $this->validPayload([
            'email' => 'bad-email',
            'gender' => 'Other',
            'password_confirmation' => 'does-not-match',
        ]));

        $response->assertSessionHasErrors(['email', 'gender', 'password']);
        $this->assertSame(0, Member::count());
        Mail::assertNothingSent();
    }

    public function test_duplicate_email_is_rejected(): void
    {
        Mail::fake();
        $this->tier();
        Member::create([
            'full_name' => 'Existing',
            'email' => 'new@example.com',
            'phone_code' => '+967',
            'phone_number' => '700000000',
            'country' => 'Yemen',
            'specialty' => 'X',
            'membership_type' => 'member',
        ]);

        $response = $this->from(route('register'))->post(route('register.post'), $this->validPayload());

        $response->assertSessionHasErrors('email');
    }

    public function test_valid_otp_creates_member_and_logs_in(): void
    {
        Mail::fake();

        $response = $this->withSession(['pending_registration' => $this->pendingRegistration()])
            ->from(route('verification.notice'))
            ->post(route('verification.verify'), ['otp' => '123456']);

        $response->assertRedirect(route('profile.show'));
        $this->assertDatabaseHas('members', [
            'email' => 'new@example.com',
            'membership_type' => 'member',
        ]);

        $member = Member::firstWhere('email', 'new@example.com');
        $this->assertNotNull($member->email_verified_at);
        $this->assertTrue(Auth::guard('member')->check());
        $response->assertSessionMissing('pending_registration');
    }

    public function test_invalid_otp_is_rejected(): void
    {
        $response = $this->withSession(['pending_registration' => $this->pendingRegistration()])
            ->from(route('verification.notice'))
            ->post(route('verification.verify'), ['otp' => '000000']);

        $response->assertSessionHasErrors('otp');
        $this->assertSame(0, Member::count());
    }

    public function test_expired_otp_is_rejected(): void
    {
        $pending = $this->pendingRegistration(['otp_expires_at' => now()->subMinute()]);

        $response = $this->withSession(['pending_registration' => $pending])
            ->from(route('verification.notice'))
            ->post(route('verification.verify'), ['otp' => '123456']);

        $response->assertSessionHasErrors('otp');
        $this->assertSame(0, Member::count());
    }

    public function test_verification_page_redirects_without_pending_registration(): void
    {
        $this->get(route('verification.notice'))->assertRedirect(route('public.login'));
    }
}
