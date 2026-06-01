<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberProfileAdminTest extends TestCase
{
    use RefreshDatabase;

    private int $seq = 0;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'super_admin']);
    }

    private function member(array $overrides = []): Member
    {
        $this->seq++;

        return Member::create(array_merge([
            'full_name' => 'Member '.$this->seq,
            'email' => 'm'.$this->seq.'@example.com',
            'phone_code' => '+967',
            'phone_number' => '71'.str_pad((string) $this->seq, 7, '0', STR_PAD_LEFT),
            'country' => 'Yemen',
            'specialty' => 'Data Analysis',
            'membership_type' => 'member',
            'password' => 'password',
        ], $overrides));
    }

    public function test_contact_view_shows_linked_member_name(): void
    {
        $member = $this->member(['full_name' => 'Linked Member Name']);
        $contact = Contact::create([
            'name' => 'Signed In Sender',
            'email' => $member->email,
            'subject' => 'Hello',
            'message' => 'A message body.',
            'member_id' => $member->id,
        ]);

        $this->actingAs($this->admin())
            ->get('/admin/contacts/'.$contact->id)
            ->assertOk()
            ->assertSee('Linked Member Name')
            ->assertDontSee('Guest (not a member)');
    }

    public function test_contact_view_shows_guest_for_unlinked_message(): void
    {
        $contact = Contact::create([
            'name' => 'Anonymous Guest',
            'email' => 'guest@example.com',
            'subject' => 'Hello',
            'message' => 'A message body.',
        ]);

        $this->actingAs($this->admin())
            ->get('/admin/contacts/'.$contact->id)
            ->assertOk()
            ->assertSee('Guest (not a member)');
    }

    public function test_member_view_loads_with_relation_managers(): void
    {
        $member = $this->member();

        $this->actingAs($this->admin())
            ->get('/admin/members/'.$member->id)
            ->assertOk();
    }
}
