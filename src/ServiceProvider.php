<?php

namespace State\Walls;

use Illuminate\Support\Facades\Route;
use Statamic\Auth\Protect\ProtectorManager;
use Statamic\Facades\CP\Nav;
use Statamic\Providers\AddonServiceProvider;
use State\Walls\Tags\Walls;
use Stripe\Stripe;

class ServiceProvider extends AddonServiceProvider
{

    protected $routes = [
        'cp' => __DIR__.'/../routes/cp.php',
        'web' => __DIR__.'/../routes/web.php',
    ];

    protected $tags = [
        Walls::class,
    ];

    public function bootAddon()
    {
        if ($this->app->runningInConsole()) {
            $this->publishResources();
        }

        $this->setUpStripe();
        $this->createNavigation();
        $this->registerProtector();
        $this->createRouteBinding();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/walls.php', 'walls');
    }

    private function createNavigation()
    {
        Nav::extend(function ($nav) {
            $nav->content('Walls')
                ->route('collections.show', 'walls')
                ->icon('entries');
        });
    }

    private function setUpStripe()
    {
        if ($secretKey = config('walls.payments.stripe.secret_key')) {
            Stripe::setApiKey($secretKey);
        }
    }

    private function publishResources()
    {
        $this->publishes([
            __DIR__.'/../config/walls.php' => config_path('walls.php'),
            __DIR__.'/../resources/js' => public_path('/vendor/state/walls/js'),
            __DIR__.'/../resources/css' => public_path('/vendor/state/walls/css'),
            __DIR__.'/../resources/blueprints' => resource_path('blueprints/collections'),
            __DIR__.'/../resources/content' => base_path('content/collections'),
        ], 'walls');
    }

    protected function createRouteBinding(): void
    {
        Route::bind('wall', function ($handle) {
            $config = config('walls.walls.'.$handle);

            return Wall::create($handle, $config);
        });
    }

    protected function registerProtector(): void
    {
        app(ProtectorManager::class)->extend('walls', fn () => new WallsProtector);
    }

}
