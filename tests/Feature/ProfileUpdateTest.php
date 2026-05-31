<?php

namespace Tests\Feature;

use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    private function member(): Member
    {
        return Member::create([
            'full_name' => 'Old Name',
            'email' => 'profile@example.com',
            'phone_code' => '+967',
            'phone_number' => '700000000',
            'country' => 'Yemen',
            'specialty' => 'Data',
            'membership_type' => 'member',
            'password' => 'old-password',
        ]);
    }

    public function test_member_can_update_their_profile(): void
    {
        $member = $this->member();

        $response = $this->actingAs($member, 'member')
            ->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'full_name' => 'New Name',
                'phone_code' => '+1',
                'phone_number' => '5551234',
                'country' => 'USA',
                'gender' => 'Female',
                'specialty' => 'Data Engineering',
                'bio' => 'Data enthusiast',
            ]);

        $response->assertRedirect(route('profile.show'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('members', [
            'id' => $member->id,
            'full_name' => 'New Name',
            'country' => 'USA',
            'gender' => 'Female',
        ]);
    }

    public function test_profile_update_requires_core_fields(): void
    {
        $member = $this->member();

        $this->actingAs($member, 'member')
            ->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'full_name' => '',
                'phone_code' => '',
                'phone_number' => '',
                'country' => '',
                'gender' => 'X',
                'specialty' => '',
            ])
            ->assertSessionHasErrors(['full_name', 'phone_code', 'phone_number', 'country', 'gender', 'specialty']);
    }

    public function test_member_can_change_password_via_profile(): void
    {
        $member = $this->member();

        $this->actingAs($member, 'member')
            ->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'full_name' => 'Old Name',
                'phone_code' => '+967',
                'phone_number' => '700000000',
                'country' => 'Yemen',
                'gender' => 'Male',
                'specialty' => 'Data',
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
            ])
            ->assertRedirect(route('profile.show'));

        $this->assertTrue(Hash::check('new-password-123', $member->fresh()->password));
    }

    public function test_guest_cannot_update_a_profile(): void
    {
        $this->put(route('profile.update'), [])->assertRedirect();
        $this->assertGuest('member');
    }
}
