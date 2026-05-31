<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Render Blade views without requiring a compiled Vite manifest,
        // so feature tests don't depend on `npm run build` (locally or in CI).
        $this->withoutVite();
    }
}
