<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_broadcast_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('broadcast_id')->constrained('email_broadcasts')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->string('email'); // snapshot of email at send time
            $table->string('tracking_token')->unique(); // UUID per recipient
            $table->timestamp('opened_at')->nullable();
            $table->unsignedInteger('open_count')->default(0);
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamps();

            $table->index('broadcast_id');
            $table->index('tracking_token');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_broadcast_recipients');
    }
};
