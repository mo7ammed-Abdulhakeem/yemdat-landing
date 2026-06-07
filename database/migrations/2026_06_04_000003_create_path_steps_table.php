<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('path_steps')) {
            return;
        }

        Schema::create('path_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_path_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);

            // Optional grouping heading, e.g. "Phase 1 — Foundations".
            $table->string('phase_en')->nullable();
            $table->string('phase_ar')->nullable();

            // Internal step → links to an existing event (UUID). External step → null.
            $table->foreignUuid('event_id')->nullable()->constrained('events')->nullOnDelete();

            // External step details (used when event_id is null).
            $table->string('title_en')->nullable();
            $table->string('title_ar')->nullable();
            $table->string('url')->nullable();
            $table->enum('resource_type', ['event', 'video', 'article', 'course', 'doc', 'other'])
                ->default('event');
            $table->text('notes_en')->nullable();
            $table->text('notes_ar')->nullable();

            $table->boolean('is_optional')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('path_steps');
    }
};
