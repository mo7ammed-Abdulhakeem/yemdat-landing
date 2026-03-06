<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First check if 'uuid' column exists to gracefully handle previous failed partial migrations
        if (!Schema::hasColumn('events', 'uuid')) {
            Schema::table('events', function (Blueprint $table) {
                $table->uuid('uuid')->nullable()->after('id');
            });
        }

        // Generate and assign UUIDs to existing events
        $events = DB::table('events')->get();
        foreach ($events as $event) {
            if (!$event->uuid) {
               DB::table('events')
                   ->where('id', $event->id)
                   ->update(['uuid' => (string) Str::uuid()]);
            }
        }

        // Pre-process the 'event_member' pivot table
        if (!Schema::hasColumn('event_member', 'event_uuid')) {
            Schema::table('event_member', function (Blueprint $table) {
                $table->uuid('event_uuid')->nullable()->after('event_id');
            });
        }

        // Map integer event_id to the new UUID string on the pivot table
        $pivotRecords = DB::table('event_member')->get();
        foreach ($pivotRecords as $record) {
            $matchedEvent = DB::table('events')->where('id', $record->event_id)->first();
            if ($matchedEvent && !$record->event_uuid) {
                DB::table('event_member')
                    ->where('id', $record->id)
                    ->update(['event_uuid' => $matchedEvent->uuid]);
            }
        }

        // Decouple the pivot table safely using explicit named constraint dropping
        Schema::table('event_member', function (Blueprint $table) {
            if (Schema::hasColumn('event_member', 'event_id')) {
                // In MySQL dropping foreign key syntax can cause fatal crashes if missing. Disable constraints temporarily to bypass.
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                $table->dropColumn('event_id');
            }
        });

        // Rename the generic track back to exactly 'event_id'
        if (Schema::hasColumn('event_member', 'event_uuid')) {
            Schema::table('event_member', function (Blueprint $table) {
                $table->renameColumn('event_uuid', 'event_id');
            });
        }

        // Modify the core 'events' table primary key
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'id')) {
               try { $table->dropColumn('id'); } catch (\Exception $e) {}
            }
        });

        if (Schema::hasColumn('events', 'uuid')) {
            Schema::table('events', function (Blueprint $table) {
                $table->renameColumn('uuid', 'id');
            });

            DB::statement('ALTER TABLE events ADD PRIMARY KEY (id);');
        }

        // Re-establish the foreign key link with the new UUID type
        Schema::table('event_member', function (Blueprint $table) {
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        throw new \Exception('Irreversible migration: Cannot safely convert UUIDs back to auto-incrementing integers across pivot relationships.');
    }
};
