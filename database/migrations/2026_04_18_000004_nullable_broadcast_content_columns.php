<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_broadcasts', function (Blueprint $table) {
            $table->string('subject_en')->nullable()->change();
            $table->string('subject_ar')->nullable()->change();
            $table->longText('body_en')->nullable()->change();
            $table->longText('body_ar')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('email_broadcasts', function (Blueprint $table) {
            $table->string('subject_en')->nullable(false)->change();
            $table->string('subject_ar')->nullable(false)->change();
            $table->longText('body_en')->nullable(false)->change();
            $table->longText('body_ar')->nullable(false)->change();
        });
    }
};
