<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventFilterTest extends TestCase
{
    use RefreshDatabase;

    private function makeEvent(array $overrides = []): Event
    {
        static $i = 0;
        $i++;

        return Event::create(array_merge([
            'title_en' => 'Event '.$i,
            'title_ar' => 'فعالية '.$i,
            'slug' => 'event-'.$i,
            'format' => 'event',
            'start_date' => now()->addWeeks($i),
            'is_active' => true,
        ], $overrides));
    }

    public function test_format_filter_returns_only_matching_events(): void
    {
        $this->makeEvent(['title_en' => 'A Plain Event', 'slug' => 'plain', 'format' => 'event']);
        $this->makeEvent(['title_en' => 'A Real Course', 'slug' => 'course', 'format' => 'course']);

        $response = $this->get('/events?format=course');

        $response->assertOk();
        $response->assertSee('A Real Course');
        $response->assertDontSee('A Plain Event');
    }

    public function test_level_filter_returns_only_matching_events(): void
    {
        $this->makeEvent(['title_en' => 'Beginner Bootcamp', 'slug' => 'beg', 'level' => 'beginner']);
        $this->makeEvent(['title_en' => 'Advanced Lab', 'slug' => 'adv', 'level' => 'advanced']);

        $response = $this->get('/events?level=advanced');

        $response->assertOk();
        $response->assertSee('Advanced Lab');
        $response->assertDontSee('Beginner Bootcamp');
    }

    public function test_search_filters_by_title(): void
    {
        $this->makeEvent(['title_en' => 'Python for Analysts', 'slug' => 'py']);
        $this->makeEvent(['title_en' => 'Tableau Masterclass', 'slug' => 'tab']);

        $response = $this->get('/events?q=Python');

        $response->assertOk();
        $response->assertSee('Python for Analysts');
        $response->assertDontSee('Tableau Masterclass');
    }

    public function test_invalid_format_is_ignored(): void
    {
        $this->makeEvent(['title_en' => 'Visible Event', 'slug' => 'vis', 'format' => 'event']);

        $response = $this->get('/events?format=bogus');

        $response->assertOk();
        $response->assertSee('Visible Event');
    }
}
