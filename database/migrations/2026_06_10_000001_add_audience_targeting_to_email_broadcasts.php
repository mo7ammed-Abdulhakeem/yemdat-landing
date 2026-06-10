<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_broadcasts', function (Blueprint $table) {
            // Was enum('all_members','event_members'). Widen to a string so new audience
            // types (by_membership_tier, trainers_only, …) fit without future enum edits.
            $table->string('audience_type')->default('all_members')->change();

            // Holds the targeted value for parameterised audiences — the membership tier
            // slug for `by_membership_tier`. Null for all_members / event_members / trainers_only.
            if (! Schema::hasColumn('email_broadcasts', 'audience_value')) {
                $table->string('audience_value')->nullable()->after('audience_type');
            }
        });
    }

    public function down(): void
    {
        // Collapse audience types that don't exist in the original enum, otherwise the
        // enum revert below would fail (data truncation) on any tier/trainer broadcast.
        DB::table('email_broadcasts')
            ->whereNotIn('audience_type', ['all_members', 'event_members'])
            ->update(['audience_type' => 'all_members']);

        Schema::table('email_broadcasts', function (Blueprint $table) {
            if (Schema::hasColumn('email_broadcasts', 'audience_value')) {
                $table->dropColumn('audience_value');
            }

            $table->enum('audience_type', ['all_members', 'event_members'])->change();
        });
    }
};
