<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            if (!Schema::hasColumn('members', 'password')) {
                $table->string('password')->nullable()->after('email');
            }
            if (!Schema::hasColumn('members', 'remember_token')) {
                $table->rememberToken()->after('password');
            }
            if (!Schema::hasColumn('members', 'bio')) {
                $table->text('bio')->nullable()->after('membership_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('members', 'password'))
                $columnsToDrop[] = 'password';
            if (Schema::hasColumn('members', 'remember_token'))
                $columnsToDrop[] = 'remember_token';
            if (Schema::hasColumn('members', 'bio'))
                $columnsToDrop[] = 'bio';

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
