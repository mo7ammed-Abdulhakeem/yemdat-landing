<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_member', function (Blueprint $table) {
            if (! Schema::hasColumn('event_member', 'attended_at')) {
                $table->timestamp('attended_at')->nullable()->after('member_id');
            }
            if (! Schema::hasColumn('event_member', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('attended_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('event_member', function (Blueprint $table) {
            $table->dropColumn(['attended_at', 'completed_at']);
        });
    }
};
