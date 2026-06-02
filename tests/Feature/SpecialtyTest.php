<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Support\SpecialtyNormalizer;
use Database\Seeders\SpecialtySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SpecialtyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SpecialtySeeder::class);
    }

    private function member(array $overrides = []): Member
    {
        return Member::create(array_merge([
            'full_name' => 'Spec Member',
            'email' => 'spec@example.com',
            'phone_code' => '+967',
            'phone_number' => '744444444',
            'country' => 'Yemen',
            'gender' => 'Male',
            'specialty' => 'data-analytics',
            'membership_type' => 'member',
            'password' => 'password',
        ], $overrides));
    }

    #[DataProvider('normalizationCases')]
    public function test_normalizer_maps_values_to_canonical_slugs(?string $specialty, ?string $other, string $expected): void
    {
        $this->assertSame($expected, SpecialtyNormalizer::normalize($specialty, $other));
    }

    public static function normalizationCases(): array
    {
        return [
            'legacy label'      => ['Data Analytics', null, 'data-analytics'],
            'lowercase variant' => ['analytics', null, 'data-analytics'],
            'ai acronym'        => ['ai', null, 'ai-ml'],
            'slug variant'      => ['data_mgmt', null, 'data-management'],
            'governance short'  => ['governance', null, 'data-governance'],
            'free text IT'      => ['other', 'IT', 'information-technology'],
            'free text CS'      => ['other', 'Computer science', 'computer-science'],
            'free text cyber'   => ['other', 'Cyber Security', 'cybersecurity'],
            'arabic accounting' => ['other', 'محاسبة', 'accounting-finance'],
            'arabic IT'         => ['other', 'تقنية المعلومات', 'information-technology'],
            'digital not IT'    => ['other', 'Digital transformation', 'business-management'],
            'already canonical' => ['information-technology', null, 'information-technology'],
            'unmappable'        => ['other', 'qwerty zzz', 'other'],
            'empty'             => ['', '', 'other'],
        ];
    }

    public function test_profile_update_accepts_a_valid_specialty_slug(): void
    {
        $member = $this->member(['specialty' => 'other']);

        $this->actingAs($member, 'member')
            ->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'full_name' => 'Spec Member',
                'phone_code' => '+967',
                'phone_number' => '744444444',
                'country' => 'Yemen',
                'gender' => 'Male',
                'specialty' => 'cybersecurity',
            ])
            ->assertRedirect(route('profile.show'));

        $this->assertSame('cybersecurity', $member->fresh()->specialty);
    }

    public function test_profile_update_rejects_an_unknown_specialty(): void
    {
        $member = $this->member();

        $this->actingAs($member, 'member')
            ->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'full_name' => 'Spec Member',
                'phone_code' => '+967',
                'phone_number' => '744444444',
                'country' => 'Yemen',
                'gender' => 'Male',
                'specialty' => 'not-a-real-slug',
            ])
            ->assertSessionHasErrors('specialty');
    }

    public function test_other_specialty_requires_a_detail(): void
    {
        $member = $this->member();

        $this->actingAs($member, 'member')
            ->from(route('profile.edit'))
            ->put(route('profile.update'), [
                'full_name' => 'Spec Member',
                'phone_code' => '+967',
                'phone_number' => '744444444',
                'country' => 'Yemen',
                'gender' => 'Male',
                'specialty' => 'other',
                'specialty_other' => '',
            ])
            ->assertSessionHasErrors('specialty_other');
    }

    public function test_specialty_label_is_localized_and_prefers_other_detail(): void
    {
        $member = $this->member(['specialty' => 'cybersecurity']);
        $this->assertSame('Cybersecurity', $member->specialty_label);

        app()->setLocale('ar');
        $this->assertSame('الأمن السيبراني', $member->fresh()->specialty_label);
        app()->setLocale('en');

        $other = $this->member([
            'email' => 'other@example.com',
            'phone_number' => '755555555',
            'specialty' => 'other',
            'specialty_other' => 'Astrophysics',
        ]);
        $this->assertSame('Astrophysics', $other->specialty_label);
    }
}
