<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration 
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing records
        DB::table('members')
            ->where('membership_type', 'individual')
            ->update(['membership_type' => 'intern']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to individual
        DB::table('members')
            ->where('membership_type', 'intern')
            ->update(['membership_type' => 'individual']);
    }
};
