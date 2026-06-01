<?php

namespace WallsTests;

use Statamic\Testing\AddonTestCase;
use State\Walls\ServiceProvider;

class TestCase extends AddonTestCase
{
    protected string $addonServiceProvider = ServiceProvider::class;

    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations();
    }
}
