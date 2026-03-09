<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration 
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if both columns exist, indicating the previous UUID migration failed halfway
        if (Schema::hasColumn('event_member', 'event_id') && Schema::hasColumn('event_member', 'event_uuid')) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // 1. Drop foreign keys dynamically via raw queries
            $foreignKeys = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'event_member' AND COLUMN_NAME = 'event_id' AND REFERENCED_TABLE_NAME IS NOT NULL");
            foreach ($foreignKeys as $fk) {
                DB::statement("ALTER TABLE event_member DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
            }

            // Also check for standard indexes that might have been left behind by the foreign key
            $indexes = DB::select("SHOW INDEX FROM event_member WHERE Column_name = 'event_id'");
            foreach ($indexes as $index) {
                if ($index->Key_name !== 'PRIMARY') {
                    DB::statement("ALTER TABLE event_member DROP INDEX `{$index->Key_name}`");
                }
            }

            // 2. Safely drop the integer column
            DB::statement("ALTER TABLE event_member DROP COLUMN event_id");

            // 3. Rename the fully populated string UUID column into the permanent 'event_id'
            DB::statement("ALTER TABLE event_member CHANGE event_uuid event_id CHAR(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL");

            // 4. Re-establish the correct foreign key mapped to the char(36) UUIDs
            DB::statement("ALTER TABLE event_member ADD CONSTRAINT event_member_event_id_foreign FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE");

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    // 
    }
};
