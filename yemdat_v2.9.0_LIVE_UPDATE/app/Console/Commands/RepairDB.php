<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RepairDB extends Command
{
    protected $signature = 'db:repair';

    public function handle()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            DB::statement('ALTER TABLE event_member DROP FOREIGN KEY event_member_event_id_foreign');
        }
        catch (\Exception $e) {
            echo "1: " . $e->getMessage() . "\n";
        }
        try {
            DB::statement('ALTER TABLE event_member DROP COLUMN event_id');
        }
        catch (\Exception $e) {
            echo "2: " . $e->getMessage() . "\n";
        }
        try {
            DB::statement('ALTER TABLE event_member CHANGE COLUMN event_uuid event_id CHAR(36) NULL');
        }
        catch (\Exception $e) {
            echo "3: " . $e->getMessage() . "\n";
        }

        try {
            DB::statement('ALTER TABLE events MODIFY id BIGINT(20) UNSIGNED NOT NULL');
        }
        catch (\Exception $e) {
            echo "4: " . $e->getMessage() . "\n";
        }
        try {
            DB::statement('ALTER TABLE events DROP PRIMARY KEY');
        }
        catch (\Exception $e) {
            echo "5: " . $e->getMessage() . "\n";
        }
        try {
            DB::statement('ALTER TABLE events DROP COLUMN id');
        }
        catch (\Exception $e) {
            echo "6: " . $e->getMessage() . "\n";
        }
        try {
            DB::statement('ALTER TABLE events CHANGE COLUMN uuid id CHAR(36) NOT NULL');
        }
        catch (\Exception $e) {
            echo "7: " . $e->getMessage() . "\n";
        }
        try {
            DB::statement('ALTER TABLE events ADD PRIMARY KEY (id)');
        }
        catch (\Exception $e) {
            echo "8: " . $e->getMessage() . "\n";
        }

        // Reconnect constraint
        try {
            DB::statement('ALTER TABLE event_member ADD CONSTRAINT event_member_event_id_foreign FOREIGN KEY (event_id) REFERENCES events (id) ON DELETE CASCADE');
        }
        catch (\Exception $e) {
            echo "9: " . $e->getMessage() . "\n";
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        echo "Done!";
    }
}
