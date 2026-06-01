<?php

namespace Tests\Feature;

use App\Models\MembershipTier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MembershipPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_membership_page_renders_in_arabic_with_malformed_features(): void
    {
        // A tier whose features_ar is a malformed JSON string (not an array) — the bug that
        // crashed the Arabic page with "count(): Argument #1 must be Countable|array".
        $tier = MembershipTier::create([
            'slug' => 'expert', 'name_en' => 'Expert', 'name_ar' => 'خبير',
            'description_en' => 'desc', 'description_ar' => 'وصف',
            'features_en' => ['Workshops'], 'features_ar' => ['ورش'],
            'is_active' => true, 'sort_order' => 1,
        ]);
        // Corrupt features_ar directly to a JSON-encoded string (bypassing the array cast).
        DB::table('membership_tiers')->where('id', $tier->id)
            ->update(['features_ar' => json_encode(',feature one,feature two')]);

        $this->withSession(['locale' => 'ar'])->get(route('membership'))->assertOk();
    }

    public function test_features_accessor_always_returns_an_array(): void
    {
        $tier = new MembershipTier(['features_en' => ['a', 'b']]);
        $this->assertSame(['a', 'b'], $tier->features);

        // Real corruption: the array column holds a JSON-encoded string (so the array cast
        // yields a string). The accessor splits it on commas and drops empties.
        $tier = new MembershipTier();
        $tier->setRawAttributes(['features_en' => json_encode(',one,two')]);
        $this->assertSame(['one', 'two'], array_values($tier->features));
    }
}
