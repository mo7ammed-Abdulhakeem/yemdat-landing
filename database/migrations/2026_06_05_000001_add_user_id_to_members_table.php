<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('members', 'user_id')) {
            return;
        }

        Schema::table('members', function (Blueprint $table) {
            // Links a member to a staff User account (used when the member is promoted
            // to a trainer). nullOnDelete: deleting the user just unlinks the member.
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('members', 'user_id')) {
            return;
        }

        Schema::table('members', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
