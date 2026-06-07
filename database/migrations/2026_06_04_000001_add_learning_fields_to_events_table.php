<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (! Schema::hasColumn('events', 'format')) {
                $table->enum('format', ['event', 'workshop', 'course'])
                    ->default('event')
                    ->after('slug');
            }
            if (! Schema::hasColumn('events', 'level')) {
                $table->enum('level', ['beginner', 'intermediate', 'advanced'])
                    ->nullable()
                    ->after('format');
            }
            if (! Schema::hasColumn('events', 'specialty')) {
                // Topic tag — references specialties.slug (same loose pattern as members.specialty).
                $table->string('specialty')->nullable()->after('level');
            }
            if (! Schema::hasColumn('events', 'outcomes_en')) {
                $table->text('outcomes_en')->nullable()->after('description_ar');
            }
            if (! Schema::hasColumn('events', 'outcomes_ar')) {
                $table->text('outcomes_ar')->nullable()->after('outcomes_en');
            }
            if (! Schema::hasColumn('events', 'prerequisites_en')) {
                $table->text('prerequisites_en')->nullable()->after('outcomes_ar');
            }
            if (! Schema::hasColumn('events', 'prerequisites_ar')) {
                $table->text('prerequisites_ar')->nullable()->after('prerequisites_en');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            foreach ([
                'format', 'level', 'specialty',
                'outcomes_en', 'outcomes_ar',
                'prerequisites_en', 'prerequisites_ar',
            ] as $column) {
                if (Schema::hasColumn('events', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
