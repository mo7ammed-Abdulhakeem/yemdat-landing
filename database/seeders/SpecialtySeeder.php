<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    /**
     * Canonical, bilingual specialty taxonomy. Idempotent (keyed by slug), so it can be
     * re-run on deploy without clobbering admin edits to names/order/visibility.
     */
    public function run(): void
    {
        $specialties = [
            ['slug' => 'data-analytics',        'name_en' => 'Data Analytics',                  'name_ar' => 'تحليل البيانات'],
            ['slug' => 'data-science',          'name_en' => 'Data Science',                    'name_ar' => 'علم البيانات'],
            ['slug' => 'data-engineering',      'name_en' => 'Data Engineering',                'name_ar' => 'هندسة البيانات'],
            ['slug' => 'data-management',       'name_en' => 'Data Management',                 'name_ar' => 'إدارة البيانات'],
            ['slug' => 'data-governance',       'name_en' => 'Data Governance',                 'name_ar' => 'حوكمة البيانات'],
            ['slug' => 'business-intelligence', 'name_en' => 'Business Intelligence',           'name_ar' => 'ذكاء الأعمال'],
            ['slug' => 'ai-ml',                 'name_en' => 'Artificial Intelligence & ML',    'name_ar' => 'الذكاء الاصطناعي وتعلم الآلة'],
            ['slug' => 'information-technology', 'name_en' => 'Information Technology (IT)',     'name_ar' => 'تقنية المعلومات'],
            ['slug' => 'computer-science',      'name_en' => 'Computer Science',                'name_ar' => 'علوم الحاسوب'],
            ['slug' => 'software-engineering',  'name_en' => 'Software Engineering',            'name_ar' => 'هندسة البرمجيات'],
            ['slug' => 'cybersecurity',         'name_en' => 'Cybersecurity',                   'name_ar' => 'الأمن السيبراني'],
            ['slug' => 'statistics-math',       'name_en' => 'Statistics & Mathematics',        'name_ar' => 'الإحصاء والرياضيات'],
            ['slug' => 'business-management',   'name_en' => 'Business & Management',           'name_ar' => 'إدارة الأعمال'],
            ['slug' => 'accounting-finance',    'name_en' => 'Accounting & Finance',            'name_ar' => 'المحاسبة والمالية'],
            ['slug' => 'other',                 'name_en' => 'Other',                           'name_ar' => 'أخرى'],
        ];

        foreach ($specialties as $i => $row) {
            // firstOrCreate: seed once, then leave admin edits (name/order/visibility) intact.
            Specialty::firstOrCreate(
                ['slug' => $row['slug']],
                [
                    'name_en' => $row['name_en'],
                    'name_ar' => $row['name_ar'],
                    'sort_order' => $i + 1,
                    'is_active' => true,
                ],
            );
        }
    }
}
