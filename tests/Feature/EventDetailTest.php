<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\LearningPath;
use App\Models\Setting;
use App\Models\Specialty;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventDetailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // The "Part of these learning paths" box only renders when the Learning
        // Paths page is live; it ships hidden by default (config/pages.php).
        Setting::updateOrCreate(['key' => 'page_paths_active'], ['value' => '1']);
    }

    private function makeEvent(array $overrides = []): Event
    {
        return Event::create(array_merge([
            'title_en' => 'Data Bootcamp',
            'title_ar' => 'معسكر البيانات',
            'slug' => 'data-bootcamp',
            'format' => 'course',
            'level' => 'beginner',
            'start_date' => now()->addWeek(),
            'end_date' => now()->addWeek()->addHours(2),
            'is_active' => true,
        ], $overrides));
    }

    public function test_detail_shows_format_level_and_specialty_badges(): void
    {
        Specialty::create([
            'slug' => 'data-science',
            'name_en' => 'Data Science',
            'name_ar' => 'علم البيانات',
            'is_active' => true,
        ]);

        $event = $this->makeEvent(['specialty' => 'data-science']);

        $response = $this->get('/events/'.$event->slug);

        $response->assertOk();
        $response->assertSee('Course');     // format badge
        $response->assertSee('Beginner');   // level badge
        $response->assertSee('Data Science'); // specialty badge
    }

    public function test_detail_shows_outcomes_and_prerequisites(): void
    {
        $event = $this->makeEvent([
            'outcomes_en' => "Write SQL queries\nBuild a dashboard",
            'prerequisites_en' => 'Basic spreadsheet skills',
        ]);

        $response = $this->get('/events/'.$event->slug);

        $response->assertOk();
        $response->assertSee("What you'll learn", false);
        $response->assertSee('Write SQL queries');
        $response->assertSee('Build a dashboard');
        $response->assertSee('Prerequisites');
        $response->assertSee('Basic spreadsheet skills');
    }

    public function test_detail_links_to_published_paths_containing_the_event(): void
    {
        $event = $this->makeEvent();

        $path = LearningPath::create([
            'slug' => 'career-path',
            'title_en' => 'Career Path',
            'title_ar' => 'المسار المهني',
            'is_published' => true,
        ]);
        $path->steps()->create([
            'sort_order' => 10,
            'event_id' => $event->id,
            'resource_type' => 'event',
        ]);

        $response = $this->get('/events/'.$event->slug);

        $response->assertOk();
        $response->assertSee('Career Path');
        $response->assertSee(route('paths.show', $path->slug), false);
    }

    public function test_detail_hides_paths_section_when_paths_page_disabled(): void
    {
        // Even for a published path, the cross-link must not show (and 404) while
        // the Learning Paths page is switched off — its default state.
        Setting::updateOrCreate(['key' => 'page_paths_active'], ['value' => '0']);

        $event = $this->makeEvent();

        $path = LearningPath::create([
            'slug' => 'career-path',
            'title_en' => 'Career Path',
            'title_ar' => 'المسار المهني',
            'is_published' => true,
        ]);
        $path->steps()->create([
            'sort_order' => 10,
            'event_id' => $event->id,
            'resource_type' => 'event',
        ]);

        $response = $this->get('/events/'.$event->slug);

        $response->assertOk();
        $response->assertDontSee('Career Path');
        $response->assertDontSee(route('paths.show', $path->slug), false);
    }

    public function test_detail_hides_paths_section_for_unpublished_paths(): void
    {
        $event = $this->makeEvent();

        $path = LearningPath::create([
            'slug' => 'draft-path',
            'title_en' => 'Draft Career Path',
            'title_ar' => 'مسار مسودة',
            'is_published' => false,
        ]);
        $path->steps()->create([
            'sort_order' => 10,
            'event_id' => $event->id,
            'resource_type' => 'event',
        ]);

        $response = $this->get('/events/'.$event->slug);

        $response->assertOk();
        $response->assertDontSee('Draft Career Path');
    }
}
