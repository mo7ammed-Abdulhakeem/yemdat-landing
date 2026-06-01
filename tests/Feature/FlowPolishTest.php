<?php

namespace Tests\Feature;

use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlowPolishTest extends TestCase
{
    use RefreshDatabase;

    private function makeMember(): Member
    {
        return Member::create([
            'full_name' => 'Toast Member',
            'email' => 'toast@example.com',
            'phone_code' => '+967',
            'phone_number' => '722222222',
            'country' => 'Yemen',
            'gender' => 'Male',
            'specialty' => 'Data Analysis',
            'membership_type' => 'member',
            'password' => 'password',
        ]);
    }

    public function test_flashed_success_is_rendered_as_an_accessible_toast(): void
    {
        $member = $this->makeMember();

        $response = $this->actingAs($member, 'member')
            ->withSession(['success' => 'Profile updated successfully.'])
            ->get(route('profile.show'));

        $response->assertOk();
        $response->assertSee('Profile updated successfully.');
        $response->assertSee('role="status"', false);
        $response->assertSee('aria-live="polite"', false);
    }

    public function test_no_toast_is_rendered_without_a_flash_message(): void
    {
        $member = $this->makeMember();

        $response = $this->actingAs($member, 'member')->get(route('profile.show'));

        $response->assertOk();
        $response->assertDontSee('role="status"', false);
    }
}
