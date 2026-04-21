<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_broadcasts', function (Blueprint $table) {
            $table->id();
            $table->string('subject_en')->nullable();
            $table->string('subject_ar')->nullable();
            $table->longText('body_en')->nullable();
            $table->longText('body_ar')->nullable();
            $table->enum('audience_type', ['all_members', 'event_members']);
            $table->string('event_id')->nullable(); // UUID FK to events
            $table->enum('language', ['en', 'ar'])->default('en');
            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable();
            $table->foreignId('sent_by')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['draft', 'sending', 'sent'])->default('draft');
            $table->unsignedInteger('total_recipients')->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_broadcasts');
    }
};
