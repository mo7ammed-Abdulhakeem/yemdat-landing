<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainerSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'trainer_form_title_en', 'value' => 'Be A Trainer'],
            ['key' => 'trainer_form_title_ar', 'value' => 'كن مدرباً'],
            ['key' => 'trainer_form_notes_en', 'value' => 'Please fill out the form below if you are interested in making a course with us.'],
            ['key' => 'trainer_form_notes_ar', 'value' => 'يرجى ملء النموذج أدناه إذا كنت مهتمًا بتقديم دورة تدريبية معنا.'],
            ['key' => 'trainer_label_name_en', 'value' => 'Full Name'],
            ['key' => 'trainer_label_name_ar', 'value' => 'الاسم الكامل'],
            ['key' => 'trainer_label_email_en', 'value' => 'Email Address'],
            ['key' => 'trainer_label_email_ar', 'value' => 'البريد الإلكتروني'],
            ['key' => 'trainer_label_phone_en', 'value' => 'Phone Number'],
            ['key' => 'trainer_label_phone_ar', 'value' => 'رقم الهاتف'],
            ['key' => 'trainer_label_country_en', 'value' => 'Country of Residency'],
            ['key' => 'trainer_label_country_ar', 'value' => 'بلد الإقامة'],
            ['key' => 'trainer_label_help_en', 'value' => 'What can you help us with?'],
            ['key' => 'trainer_label_help_ar', 'value' => 'بماذا يمكنك مساعدتنا؟'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
            ['key' => $setting['key']],
            ['value' => $setting['value']]
            );
        }
    }
}
