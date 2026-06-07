<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_page_off_by_default_returns_404_and_hides_nav_link(): void
    {
        // 'paths' defaults to false in config/pages.php (hidden until made live).
        $this->get('/paths')->assertNotFound();

        $home = $this->get('/');
        $home->assertOk();
        $home->assertDontSee(route('paths.index'), false);
    }

    public function test_enabling_a_page_makes_it_load_and_appear_in_nav(): void
    {
        Setting::updateOrCreate(['key' => 'page_paths_active'], ['value' => '1']);

        $this->get('/paths')->assertOk();

        $home = $this->get('/');
        $home->assertSee(route('paths.index'), false);
    }

    public function test_disabling_a_default_on_page_returns_404(): void
    {
        // 'news' defaults to true; turn it off.
        Setting::updateOrCreate(['key' => 'page_news_active'], ['value' => '0']);

        $this->get('/news')->assertNotFound();
    }

    public function test_unmanaged_routes_are_unaffected(): void
    {
        Setting::updateOrCreate(['key' => 'page_paths_active'], ['value' => '0']);

        // /events is intentionally not a managed page.
        $this->get('/events')->assertOk();
    }
}
