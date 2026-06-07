<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\LearningPath;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PathsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // The Learning Paths page ships hidden by default (config/pages.php);
        // these tests exercise it as if it's been switched live.
        Setting::updateOrCreate(['key' => 'page_paths_active'], ['value' => '1']);
    }

    private function makePath(array $overrides = []): LearningPath
    {
        return LearningPath::create(array_merge([
            'slug' => 'data-career',
            'title_en' => 'Data Career Path',
            'title_ar' => 'مسار مهنة البيانات',
            'summary_en' => 'Become a data professional.',
            'summary_ar' => 'كن محترف بيانات.',
            'is_published' => true,
            'sort_order' => 1,
        ], $overrides));
    }

    public function test_paths_index_lists_only_published_paths(): void
    {
        $this->makePath(['slug' => 'published', 'title_en' => 'Published Path']);
        $this->makePath(['slug' => 'draft', 'title_en' => 'Draft Path', 'is_published' => false]);

        $response = $this->get('/paths');

        $response->assertOk();
        $response->assertSee('Published Path');
        $response->assertDontSee('Draft Path');
    }

    public function test_path_detail_renders_internal_and_external_steps(): void
    {
        $path = $this->makePath();

        $event = Event::create([
            'title_en' => 'Intro to SQL',
            'title_ar' => 'مقدمة في SQL',
            'slug' => 'intro-to-sql',
            'start_date' => now()->addWeek(),
            'is_active' => true,
        ]);

        $path->steps()->create([
            'sort_order' => 10,
            'phase_en' => 'Phase 1',
            'phase_ar' => 'المرحلة الأولى',
            'event_id' => $event->id,
            'resource_type' => 'event',
        ]);

        $path->steps()->create([
            'sort_order' => 20,
            'title_en' => 'Read the SQL handbook',
            'title_ar' => 'اقرأ دليل SQL',
            'url' => 'https://example.com/sql',
            'resource_type' => 'article',
        ]);

        $response = $this->get('/paths/'.$path->slug);

        $response->assertOk();
        $response->assertSee('Intro to SQL');               // internal step (event title)
        $response->assertSee('Read the SQL handbook');      // external step
        $response->assertSee('https://example.com/sql', false);
        $response->assertSee('Phase 1');                    // phase heading
    }

    public function test_unpublished_path_detail_returns_404(): void
    {
        $path = $this->makePath(['slug' => 'hidden', 'is_published' => false]);

        $this->get('/paths/'.$path->slug)->assertNotFound();
    }

    public function test_unknown_path_returns_404(): void
    {
        $this->get('/paths/does-not-exist')->assertNotFound();
    }
}
