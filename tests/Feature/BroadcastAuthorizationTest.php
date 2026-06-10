<?php

namespace Tests\Feature;

use App\Filament\Resources\EmailBroadcasts\EmailBroadcastResource;
use App\Models\EmailBroadcast;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The EmailBroadcasts resource gates every operation on the `broadcasts` permission.
 * These tests pin the view-page gate specifically: without an explicit canView() override
 * Filament's policy-less default would allow any panel user to read broadcast content/stats.
 */
class BroadcastAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function broadcast(): EmailBroadcast
    {
        $admin = User::factory()->create(['role' => 'super_admin']);

        return EmailBroadcast::create([
            'subject_en' => 'Hello',
            'body_en' => '<p>Hi</p>',
            'audience_type' => 'all_members',
            'language' => 'en',
            'status' => 'sent',
            'sent_by' => $admin->id,
        ]);
    }

    public function test_admin_without_broadcasts_permission_cannot_view_or_list(): void
    {
        $broadcast = $this->broadcast();
        $this->actingAs(User::factory()->create(['role' => 'admin', 'permissions' => []]));

        $this->assertFalse(EmailBroadcastResource::canViewAny());
        $this->assertFalse(EmailBroadcastResource::canView($broadcast));
    }

    public function test_admin_with_broadcasts_permission_can_view(): void
    {
        $broadcast = $this->broadcast();
        $this->actingAs(User::factory()->create(['role' => 'admin', 'permissions' => ['broadcasts']]));

        $this->assertTrue(EmailBroadcastResource::canView($broadcast));
    }

    public function test_super_admin_can_view(): void
    {
        $broadcast = $this->broadcast();
        $this->actingAs(User::factory()->create(['role' => 'super_admin']));

        $this->assertTrue(EmailBroadcastResource::canView($broadcast));
    }
}
