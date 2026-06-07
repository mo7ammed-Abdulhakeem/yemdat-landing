<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('events', 'trainer_id')) {
            return;
        }

        Schema::table('events', function (Blueprint $table) {
            // The trainer (staff User, role=trainer) assigned to teach this event.
            // Scopes the trainer's "My Events". nullOnDelete: removing the trainer unassigns.
            $table->foreignId('trainer_id')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('events', 'trainer_id')) {
            return;
        }

        Schema::table('events', function (Blueprint $table) {
            $table->dropConstrainedForeignId('trainer_id');
        });
    }
};
