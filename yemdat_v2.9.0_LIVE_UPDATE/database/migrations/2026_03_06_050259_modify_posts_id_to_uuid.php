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
        // 1. Add a temporary UUID column
        Schema::table('posts', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id');
        });

        // 2. Populate the UUID column for existing records
        $posts = DB::table('posts')->get();
        foreach ($posts as $post) {
            DB::table('posts')
                ->where('id', $post->id)
                ->update(['uuid' => (string)Str::uuid()]);
        }

        // 3. Drop the old auto-incrementing integer ID
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('id');
        });

        // 4. Rename the UUID column to 'id' and make it the primary key
        Schema::table('posts', function (Blueprint $table) {
            $table->renameColumn('uuid', 'id');
        });

        Schema::table('posts', function (Blueprint $table) {
            // Depending heavily on the DB engine, adding primary key down the line on existing column 
            // might be tricky in SQLite, so we recreate the index.
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting this complex of an operation (UUID back to integer without knowing the exact previous mapping)
        // is destructive, so we leave it empty or throw an exception.
        throw new \Exception('Irreversible migration: Cannot safely convert UUIDs back to auto-incrementing integers.');
    }
};
