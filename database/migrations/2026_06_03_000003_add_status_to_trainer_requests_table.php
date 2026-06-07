<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('trainer_requests', 'status')) {
            return;
        }

        Schema::table('trainer_requests', function (Blueprint $table) {
            // new | replied | closed
            $table->string('status')->default('new')->index()->after('agreed_to_free_provision');
        });
    }

    public function down(): void
    {
        Schema::table('trainer_requests', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
