<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_open_the_analytics_dashboard(): void
    {
        $super = User::factory()->create(['role' => 'super_admin']);

        $this->actingAs($super)->get('/admin/analytics')->assertOk();
    }

    public function test_admin_with_analytics_permission_can_open_it(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'permissions' => ['analytics']]);

        $this->actingAs($admin)->get('/admin/analytics')->assertOk();
    }

    public function test_admin_without_analytics_permission_is_forbidden(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'permissions' => []]);

        $this->actingAs($admin)->get('/admin/analytics')->assertForbidden();
    }

    public function test_home_dashboard_still_loads(): void
    {
        $super = User::factory()->create(['role' => 'super_admin']);

        $this->actingAs($super)->get('/admin')->assertOk();
    }
}
