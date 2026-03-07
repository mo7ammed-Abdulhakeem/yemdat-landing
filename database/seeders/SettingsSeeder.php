<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'site_facebook', 'value' => 'https://facebook.com'],
            ['key' => 'site_twitter', 'value' => 'https://twitter.com'],
            ['key' => 'site_instagram', 'value' => 'https://instagram.com'],
            ['key' => 'site_linkedin', 'value' => 'https://linkedin.com'],
            ['key' => 'notification_bar_enabled', 'value' => '0'],
            ['key' => 'notification_bar_text', 'value' => 'Experimental Platform: Features under development.'],
            ['key' => 'trainer_label_program_type_en', 'value' => 'Program Type'],
            ['key' => 'trainer_label_program_type_ar', 'value' => 'نوع البرنامج'],
            ['key' => 'trainer_label_duration_en', 'value' => 'Duration (Hours)'],
            ['key' => 'trainer_label_duration_ar', 'value' => 'المدة (بالساعات)'],
            ['key' => 'trainer_label_agenda_en', 'value' => 'Program/Workshop Agenda'],
            ['key' => 'trainer_label_agenda_ar', 'value' => 'أجندة البرنامج/الدورة'],
            ['key' => 'trainer_agreement_text_en', 'value' => 'I agree that if my program is approved, it will be provided for free for all participants.'],
            ['key' => 'trainer_agreement_text_ar', 'value' => 'أوافق على أنه في حال الموافقة على برنامجي، سيتم تقديمه مجاناً لجميع المشاركين.'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
