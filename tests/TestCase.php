<?php

namespace WallsTests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Statamic\Extend\Manifest;
use Statamic\Providers\StatamicServiceProvider;
use Statamic\Statamic;
use State\Walls\ServiceProvider;


class TestCase extends OrchestraTestCase
{

    protected function getPackageProviders($app)
    {
        return [
            StatamicServiceProvider::class,
            ServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Statamic' => Statamic::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app->make(Manifest::class)->manifest = [
            'state/walls' => [
                'id'        => 'state/walls',
                'namespace' => 'State\\Walls',
            ],
        ];

        Statamic::pushWebRoutes(function () {
            return require_once realpath(__DIR__.'/../routes/web.php');
        });
    }


    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations();
    }
}
