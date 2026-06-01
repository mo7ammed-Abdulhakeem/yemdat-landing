<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('certificates')) {
            return;
        }

        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('serial')->unique();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('event_id')->constrained()->cascadeOnDelete();
            $table->string('type')->default('completion');
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();

            // One certificate per (member, event)
            $table->unique(['member_id', 'event_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
