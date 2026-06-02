<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Smoke tests: every public-facing GET route should render without error.
 * This is the baseline safety net for the upcoming design-system refactor.
 */
class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public static function publicRoutes(): array
    {
        return [
            'home' => ['/'],
            'about' => ['/about'],
            'vision' => ['/vision'],
            'news index' => ['/news'],
            'events index' => ['/events'],
            'contact' => ['/contact'],
            'membership' => ['/membership'],
            'be a trainer' => ['/be-a-trainer'],
        ];
    }

    #[DataProvider('publicRoutes')]
    public function test_public_pages_load_for_guests(string $uri): void
    {
        $this->get($uri)->assertOk();
    }

    public static function guestAuthRoutes(): array
    {
        return [
            'member login' => ['/login'],
            'member register' => ['/register'],
            'forgot password' => ['/forgot-password'],
            'claim profile' => ['/claim-profile'],
            'admin login' => ['/admin/login'],
        ];
    }

    #[DataProvider('guestAuthRoutes')]
    public function test_auth_pages_load_for_guests(string $uri): void
    {
        $this->get($uri)->assertOk();
    }

    public function test_training_redirects_to_events(): void
    {
        $this->get('/training')->assertRedirect(route('events.index'));
    }

    public function test_language_switch_sets_session_locale(): void
    {
        $this->get('/lang/ar', ['referer' => route('home')]);
        $this->assertSame('ar', session('locale'));

        $this->get('/lang/en', ['referer' => route('home')]);
        $this->assertSame('en', session('locale'));
    }

    public function test_invalid_locale_is_ignored(): void
    {
        $this->get('/lang/fr', ['referer' => route('home')]);
        $this->assertNotSame('fr', session('locale'));
    }

    public function test_unknown_route_returns_404(): void
    {
        $this->get('/this-route-does-not-exist')->assertNotFound();
    }
}
