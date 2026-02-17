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
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
