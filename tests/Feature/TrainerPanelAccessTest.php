<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainerPanelAccessTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role): User
    {
        static $i = 0;
        $i++;

        return User::create([
            'name' => ucfirst($role).' '.$i,
            'email' => "{$role}{$i}@example.com",
            'password' => 'password',
            'role' => $role,
        ]);
    }

    public function test_trainer_can_access_trainer_panel(): void
    {
        $trainer = $this->makeUser('trainer');

        $this->actingAs($trainer)
            ->get('/trainer/events/trainer-events')
            ->assertOk();
    }

    public function test_trainer_cannot_access_admin_panel(): void
    {
        $trainer = $this->makeUser('trainer');

        $this->actingAs($trainer)->get('/admin')->assertForbidden();
    }

    public function test_admin_cannot_access_trainer_panel(): void
    {
        $admin = $this->makeUser('admin');

        $this->actingAs($admin)
            ->get('/trainer/events/trainer-events')
            ->assertForbidden();
    }

    public function test_guest_is_redirected_to_trainer_login(): void
    {
        $this->get('/trainer/events/trainer-events')
            ->assertRedirect('/trainer/login');
    }
}
