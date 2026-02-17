<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MembershipTier;

class MembershipTierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiers = [
            [
                'slug' => 'intern',
                'name_en' => 'Intern Membership',
                'name_ar' => 'عضوية متدرب',
                'description_en' => 'For students, fresh graduates, and individuals starting their career in data and AI.',
                'description_ar' => 'للطلاب والخريجين الجدد والأفراد المهتمين ببدء مسيرتهم في البيانات.',
                'features_en' => [
                    'Access to specialized workshops',
                    'Networking with industry leaders',
                    'Participation in annual events',
                    'Contribution to national data standards'
                ],
                'features_ar' => [
                    'الوصول إلى ورش عمل متخصصة',
                    'التواصل مع قادة المجال',
                    'المشاركة في الفعاليات السنوية',
                    'المساهمة في معايير البيانات الوطنية'
                ],
                'sort_order' => 1,
            ],
            [
                'slug' => 'expert',
                'name_en' => 'Expert & Academic Membership',
                'name_ar' => 'عضوية الخبراء والأكاديميين',
                'description_en' => 'For specialized experts and academics.',
                'description_ar' => 'للخبراء والأكاديميين المتخصصين.',
                'features_en' => [
                    'Access to specialized workshops',
                    'Networking with industry leaders',
                    'Participation in annual events',
                    'Contribution to national data standards'
                ],
                'features_ar' => [
                    'الوصول إلى ورش عمل متخصصة',
                    'التواصل مع قادة المجال',
                    'المشاركة في الفعاليات السنوية',
                    'المساهمة في معايير البيانات الوطنية'
                ],
                'sort_order' => 2,
            ],
            [
                'slug' => 'corporate',
                'name_en' => 'Corporate Membership',
                'name_ar' => 'عضوية مؤسسية للجهات الداعمة',
                'description_en' => 'For supporting organizations and companies.',
                'description_ar' => 'للمؤسسات والشركات الداعمة.',
                'features_en' => [
                    'Access to specialized workshops',
                    'Networking with industry leaders',
                    'Participation in annual events',
                    'Contribution to national data standards'
                ],
                'features_ar' => [
                    'الوصول إلى ورش عمل متخصصة',
                    'التواصل مع قادة المجال',
                    'المشاركة في الفعاليات السنوية',
                    'المساهمة في معايير البيانات الوطنية'
                ],
                'sort_order' => 3,
            ]
        ];

        foreach ($tiers as $tier) {
            MembershipTier::updateOrCreate(['slug' => $tier['slug']], $tier);
        }
    }
}
