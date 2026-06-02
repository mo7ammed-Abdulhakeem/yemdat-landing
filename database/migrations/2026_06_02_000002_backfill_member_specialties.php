<?php

use App\Models\Member;
use App\Support\SpecialtyNormalizer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * One-way, non-destructive data cleanup: map every member's free/legacy `specialty` to a
     * canonical taxonomy slug. `specialty_other` is preserved untouched, so re-running is safe and
     * nothing is lost. (No-op on a fresh install with no members.)
     */
    public function up(): void
    {
        if (! Schema::hasTable('members') || ! Schema::hasTable('specialties')) {
            return;
        }

        Member::query()
            ->select('id', 'specialty', 'specialty_other')
            ->chunkById(200, function ($members) {
                foreach ($members as $member) {
                    $slug = SpecialtyNormalizer::normalize($member->specialty, $member->specialty_other);

                    if ($slug !== $member->specialty) {
                        // Direct update: skip model events/timestamps so we don't churn updated_at.
                        DB::table('members')->where('id', $member->id)->update(['specialty' => $slug]);
                    }
                }
            });
    }

    public function down(): void
    {
        // Data cleanup only; original free-text remains in `specialty_other`. Nothing to revert.
    }
};
