<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('email_replies')) {
            return;
        }

        Schema::create('email_replies', function (Blueprint $table) {
            $table->id();
            // Polymorphic owner — a Contact or a TrainerRequest (or anything repliable later).
            $table->morphs('replyable');
            $table->string('from_name');
            $table->string('from_email');
            $table->string('subject');
            $table->longText('body');
            $table->foreignId('sent_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_replies');
    }
};
