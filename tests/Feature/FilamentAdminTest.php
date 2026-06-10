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
        $admin = User::factory()->create(['role' => 'super_admin']);

        $this->actingAs($admin)->get('/admin/members')->assertOk();
    }

    public function test_admin_can_load_the_events_and_posts_resources(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);

        $this->actingAs($admin)->get('/admin/events')->assertOk();
        $this->actingAs($admin)->get('/admin/posts')->assertOk();
    }

    public function test_admin_can_load_the_remaining_resources(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);

        foreach ([
            '/admin/membership-tiers',
            '/admin/trainer-requests',
            '/admin/contacts',
            '/admin/email-templates',
            '/admin/users',
        ] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    public function test_admin_can_load_create_forms(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);

        $this->actingAs($admin)->get('/admin/users/create')->assertOk();
        $this->actingAs($admin)->get('/admin/events/create')->assertOk();
        $this->actingAs($admin)->get('/admin/membership-tiers/create')->assertOk();
    }

    public function test_broadcasts_resource_respects_permission(): void
    {
        $super = User::factory()->create(['role' => 'super_admin']);
        $this->actingAs($super)->get('/admin/email-broadcasts')->assertOk();
        $this->actingAs($super)->get('/admin/email-broadcasts/create')->assertOk();

        // An admin without the 'broadcasts' permission must not reach the resource.
        $plain = User::factory()->create(['role' => 'admin', 'permissions' => []]);
        $this->actingAs($plain)->get('/admin/email-broadcasts')->assertForbidden();
    }

    public function test_events_and_certificates_respect_the_events_permission(): void
    {
        $super = User::factory()->create(['role' => 'super_admin']);
        $this->actingAs($super)->get('/admin/events')->assertOk();
        $this->actingAs($super)->get('/admin/certificates')->assertOk();

        // An admin without the 'events' permission must reach neither resource.
        $plain = User::factory()->create(['role' => 'admin', 'permissions' => []]);
        $this->actingAs($plain)->get('/admin/events')->assertForbidden();
        $this->actingAs($plain)->get('/admin/certificates')->assertForbidden();

        // Granting 'events' opens both (certificates are gated under the same key).
        $permitted = User::factory()->create(['role' => 'admin', 'permissions' => ['events']]);
        $this->actingAs($permitted)->get('/admin/events')->assertOk();
        $this->actingAs($permitted)->get('/admin/certificates')->assertOk();
    }

    public function test_resources_respect_granular_permissions(): void
    {
        // Admin granted only the 'members' permission.
        $limited = User::factory()->create(['role' => 'admin', 'permissions' => ['members']]);
        $this->actingAs($limited)->get('/admin/members')->assertOk();
        $this->actingAs($limited)->get('/admin/posts')->assertForbidden();   // needs 'posts'
        $this->actingAs($limited)->get('/admin/users')->assertForbidden();   // super-admin only

        // Super admin bypasses every gate.
        $super = User::factory()->create(['role' => 'super_admin']);
        $this->actingAs($super)->get('/admin/users')->assertOk();
        $this->actingAs($super)->get('/admin/posts')->assertOk();
    }

    public function test_settings_page_respects_permission(): void
    {
        $super = User::factory()->create(['role' => 'super_admin']);
        $this->actingAs($super)->get('/admin/manage-settings')->assertOk();

        $plain = User::factory()->create(['role' => 'admin', 'permissions' => []]);
        $this->actingAs($plain)->get('/admin/manage-settings')->assertForbidden();
    }

    public function test_dashboard_loads_with_and_without_analytics_widget(): void
    {
        // Analytics widget is visible to a permitted admin; dashboard renders either way.
        $analyst = User::factory()->create(['role' => 'admin', 'permissions' => ['analytics']]);
        $this->actingAs($analyst)->get('/admin')->assertOk();

        $plain = User::factory()->create(['role' => 'admin', 'permissions' => []]);
        $this->actingAs($plain)->get('/admin')->assertOk();
    }
}
