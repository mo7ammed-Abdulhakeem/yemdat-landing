<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilamentAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_the_filament_panel(): void
    {
        $this->get('/admin')->assertRedirect();
        $this->assertGuest();
    }

    public function test_admin_can_load_the_panel_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);

        $this->actingAs($admin)->get('/admin')->assertOk();
    }

    public function test_admin_can_load_the_members_resource(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get('/admin/members')->assertOk();
    }

    public function test_admin_can_load_the_events_and_posts_resources(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get('/admin/events')->assertOk();
        $this->actingAs($admin)->get('/admin/posts')->assertOk();
    }
}
