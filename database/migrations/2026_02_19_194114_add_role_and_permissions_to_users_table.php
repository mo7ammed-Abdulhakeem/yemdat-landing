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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('admin')->after('email'); // 'super_admin' or 'admin'
            }
            if (!Schema::hasColumn('users', 'permissions')) {
                $table->text('permissions')->nullable()->after('role'); // JSON array of allowed menus
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('users', 'role'))
                $columnsToDrop[] = 'role';
            if (Schema::hasColumn('users', 'permissions'))
                $columnsToDrop[] = 'permissions';

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
