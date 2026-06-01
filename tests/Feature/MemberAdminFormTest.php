<?php

namespace Tests\Feature;

use App\Filament\Resources\Members\Pages\EditMember;
use App\Models\Member;
use App\Models\MembershipTier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class MemberAdminFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        MembershipTier::create([
            'slug' => 'member', 'name_en' => 'Member', 'name_ar' => 'عضو',
            'description_en' => 'x', 'description_ar' => 'x',
            'features_en' => ['a'], 'features_ar' => ['ا'],
            'is_active' => true, 'sort_order' => 1,
        ]);

        $this->actingAs(User::factory()->create(['role' => 'super_admin']));
    }

    private function makeMember(): Member
    {
        return Member::create([
            'full_name' => 'Orig Name',
            'email' => 'orig@example.com',
            'phone_code' => '+967',
            'phone_number' => '733333333',
            'country' => 'Yemen',
            'gender' => 'Male',
            'specialty' => 'Data',
            'membership_type' => 'member',
            'password' => 'secret-password',
        ]);
    }

    public function test_editing_a_member_without_a_new_password_keeps_the_existing_one(): void
    {
        $member = $this->makeMember();

        Livewire::test(EditMember::class, ['record' => $member->getRouteKey()])
            ->fillForm(['full_name' => 'Updated Name'])
            ->call('save')
            ->assertHasNoFormErrors();

        $member->refresh();
        $this->assertSame('Updated Name', $member->full_name);
        $this->assertTrue(Hash::check('secret-password', $member->password), 'Password must be preserved when left blank.');
    }

    public function test_editing_a_member_with_a_new_password_updates_it(): void
    {
        $member = $this->makeMember();

        Livewire::test(EditMember::class, ['record' => $member->getRouteKey()])
            ->fillForm(['password' => 'brand-new-pass'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertTrue(Hash::check('brand-new-pass', $member->refresh()->password));
    }

    public function test_member_form_does_not_expose_otp_fields(): void
    {
        $member = $this->makeMember();

        Livewire::test(EditMember::class, ['record' => $member->getRouteKey()])
            ->assertFormFieldExists('full_name')
            ->assertFormFieldDoesNotExist('otp_code')
            ->assertFormFieldDoesNotExist('otp_expires_at');
    }
}
