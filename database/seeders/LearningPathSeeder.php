<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\LearningPath;
use Illuminate\Database\Seeder;

class LearningPathSeeder extends Seeder
{
    /**
     * Seed the flagship career-change path. Idempotent (keyed by slug): the path is
     * created once and steps are only seeded when it has none, so re-running on deploy
     * never clobbers admin edits.
     */
    public function run(): void
    {
        $path = LearningPath::firstOrCreate(
            ['slug' => 'start-your-data-career'],
            [
                'title_en' => 'Start Your Data Career',
                'title_ar' => 'ابدأ مسارك في البيانات',
                'summary_en' => 'A guided, step-by-step roadmap for career changers entering the data field — from zero to your first portfolio project.',
                'summary_ar' => 'خارطة طريق موجهة خطوة بخطوة للراغبين في تغيير مسارهم المهني نحو مجال البيانات — من الصفر حتى أول مشروع في معرض أعمالك.',
                'description_en' => 'New to data or switching careers? This path walks you through the essentials in a sensible order: build foundations, learn the core analysis tools, then apply them on a real project. Each step links to a Yemdat session or a hand-picked free resource.',
                'description_ar' => 'هل أنت جديد في مجال البيانات أو ترغب في تغيير مسارك المهني؟ يأخذك هذا المسار عبر الأساسيات بترتيب منطقي: ابنِ الأساس، تعلّم أدوات التحليل الأساسية، ثم طبّقها في مشروع حقيقي. كل خطوة مرتبطة بجلسة من يمدات أو بمصدر مجاني مختار بعناية.',
                'level' => 'beginner',
                'specialty' => 'data-analytics',
                'estimated_weeks' => 12,
                'is_published' => true,
                'sort_order' => 1,
            ],
        );

        if ($path->steps()->count() > 0) {
            return;
        }

        // Use a couple of real active events for the internal steps, when available.
        $events = Event::where('is_active', true)->orderBy('start_date')->take(2)->get();

        $steps = [
            [
                'phase_en' => 'Phase 1 — Foundations', 'phase_ar' => 'المرحلة الأولى — الأساسيات',
                'title_en' => 'Understand the data landscape', 'title_ar' => 'افهم مشهد البيانات',
                'url' => 'https://www.youtube.com/results?search_query=what+is+data+analytics',
                'resource_type' => 'video',
                'notes_en' => 'Get the big picture: what analysts, scientists and engineers actually do.',
                'notes_ar' => 'احصل على الصورة الكاملة: ماذا يفعل المحللون وعلماء ومهندسو البيانات فعليًا.',
            ],
            [
                'phase_en' => 'Phase 1 — Foundations', 'phase_ar' => 'المرحلة الأولى — الأساسيات',
                'title_en' => 'Spreadsheets & Excel fundamentals', 'title_ar' => 'أساسيات الجداول وإكسل',
                'url' => 'https://support.microsoft.com/excel',
                'resource_type' => 'article',
                'notes_en' => 'Comfort with spreadsheets is the fastest first win for a career changer.',
                'notes_ar' => 'إتقان الجداول هو أسرع إنجاز أولي للراغب في تغيير مساره المهني.',
            ],
            [
                'phase_en' => 'Phase 2 — Core Skills', 'phase_ar' => 'المرحلة الثانية — المهارات الأساسية',
                'title_en' => 'SQL for data analysis', 'title_ar' => 'SQL لتحليل البيانات',
                'url' => 'https://sqlbolt.com/',
                'resource_type' => 'course',
                'notes_en' => 'Querying databases is a non-negotiable skill. Practice interactively.',
                'notes_ar' => 'الاستعلام من قواعد البيانات مهارة لا غنى عنها. تدرّب بشكل تفاعلي.',
            ],
            [
                'phase_en' => 'Phase 2 — Core Skills', 'phase_ar' => 'المرحلة الثانية — المهارات الأساسية',
                'title_en' => 'Data visualization basics', 'title_ar' => 'أساسيات تصور البيانات',
                'url' => 'https://www.youtube.com/results?search_query=data+visualization+for+beginners',
                'resource_type' => 'video',
                'notes_en' => 'Learn to turn numbers into clear, honest charts.',
                'notes_ar' => 'تعلّم تحويل الأرقام إلى رسوم بيانية واضحة وصادقة.',
            ],
            [
                'phase_en' => 'Phase 3 — Build & Apply', 'phase_ar' => 'المرحلة الثالثة — البناء والتطبيق',
                'title_en' => 'Build your first portfolio project', 'title_ar' => 'ابنِ أول مشروع في معرض أعمالك',
                'url' => 'https://www.kaggle.com/datasets',
                'resource_type' => 'doc',
                'notes_en' => 'Pick a dataset you care about, analyze it end-to-end, and publish it.',
                'notes_ar' => 'اختر مجموعة بيانات تهمك، حلّلها من البداية للنهاية، وانشرها.',
                'is_optional' => false,
            ],
        ];

        $sort = 0;
        foreach ($steps as $step) {
            $path->steps()->create(array_merge([
                'sort_order' => $sort += 10,
                'is_optional' => false,
            ], $step));
        }

        // Append any real Yemdat events as live internal steps in the final phase.
        foreach ($events as $event) {
            $path->steps()->create([
                'sort_order' => $sort += 10,
                'phase_en' => 'Phase 3 — Build & Apply',
                'phase_ar' => 'المرحلة الثالثة — البناء والتطبيق',
                'event_id' => $event->id,
                'resource_type' => 'event',
                'notes_en' => 'Attend this Yemdat session and apply what you have learned.',
                'notes_ar' => 'احضر جلسة يمدات هذه وطبّق ما تعلّمته.',
            ]);
        }
    }
}
