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
        Schema::table('events', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['title', 'description', 'lecturer_name', 'lecturer_title']);

            // Add new bilingual columns
            $table->string('title_en')->after('id');
            $table->string('title_ar')->after('title_en');

            $table->longText('description_en')->nullable()->after('slug');
            $table->longText('description_ar')->nullable()->after('description_en');

            $table->string('lecturer_name_en')->nullable()->after('image');
            $table->string('lecturer_name_ar')->nullable()->after('lecturer_name_en');

            $table->string('lecturer_title_en')->nullable()->after('lecturer_name_ar');
            $table->string('lecturer_title_ar')->nullable()->after('lecturer_title_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'title_en', 'title_ar',
                'description_en', 'description_ar',
                'lecturer_name_en', 'lecturer_name_ar',
                'lecturer_title_en', 'lecturer_title_ar'
            ]);

            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('lecturer_name')->nullable();
            $table->string('lecturer_title')->nullable();
        });
    }
};
