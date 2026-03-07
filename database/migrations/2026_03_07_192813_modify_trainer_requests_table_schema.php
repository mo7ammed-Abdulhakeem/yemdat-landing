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
        Schema::table('trainer_requests', function (Blueprint $table) {
            $table->string('program_type')->after('country')->nullable();
            $table->integer('duration_hours')->after('program_type')->nullable();
            $table->text('agenda')->after('duration_hours')->nullable();
            $table->boolean('agreed_to_free_provision')->after('agenda')->default(false);

            $table->dropColumn('help_topic');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trainer_requests', function (Blueprint $table) {
            $table->dropColumn(['program_type', 'duration_hours', 'agenda', 'agreed_to_free_provision']);
            $table->text('help_topic')->nullable();
        });
    }
};
