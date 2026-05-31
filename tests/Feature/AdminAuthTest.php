<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_log_in_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'secret-password',
        ]);

        $response = $this->from(route('login'))->post(route('login.post'), [
            'email' => 'admin@example.com',
            'password' => 'secret-password',
        ]);

        $response->assertRedirect('/admincpanel/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_rejects_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'secret-password',
        ]);

        $response = $this->from(route('login'))->post(route('login.post'), [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_guest_is_redirected_from_admin_area(): void
    {
        $this->get(route('admin.broadcasts.index'))->assertRedirect(route('login'));
    }

    public function test_admin_without_broadcasts_permission_is_forbidden(): void
    {
        $user = User::factory()->create(['role' => 'admin', 'permissions' => []]);

        $this->actingAs($user)
            ->get(route('admin.broadcasts.index'))
            ->assertForbidden();
    }

    public function test_admin_with_broadcasts_permission_can_access(): void
    {
        $user = User::factory()->create(['role' => 'admin', 'permissions' => ['broadcasts']]);

        $this->actingAs($user)
            ->get(route('admin.broadcasts.index'))
            ->assertOk();
    }

    public function test_super_admin_bypasses_permission_checks(): void
    {
        $user = User::factory()->create(['role' => 'super_admin', 'permissions' => []]);

        $this->actingAs($user)
            ->get(route('admin.broadcasts.index'))
            ->assertOk();
    }
}
