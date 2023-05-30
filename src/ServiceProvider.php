<?php

namespace State\Gated;

use Illuminate\Support\Facades\Route;
use Statamic\Auth\Protect\ProtectorManager;
use Statamic\Facades\Addon;
use Statamic\Facades\CP\Nav;
use Statamic\Providers\AddonServiceProvider;
use Statamic\Support\Str;
use State\Gated\Http\Middleware\GateMiddleware;
use State\Gated\Tags\Gated;
use Stripe\Stripe;

class ServiceProvider extends AddonServiceProvider
{

    protected $routes = [
        'cp' => __DIR__.'/../routes/cp.php',
        'web' => __DIR__.'/../routes/web.php',
    ];

    protected $tags = [
        Gated::class,
    ];

    public function boot()
    {
        parent::boot();

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
        $this->mergeConfigFrom(__DIR__.'/../config/gated.php', 'gated');
    }

    private function createNavigation()
    {
        Nav::extend(function ($nav) {
            $nav->content('Gated')
                ->route('collections.show', 'gates')
                ->icon('entries');
        });
    }

    private function setUpStripe()
    {
        Stripe::setApiKey(config('gated.payments.stripe.secret_key'));
    }

    private function publishResources()
    {
        $this->publishes([
            __DIR__.'/../config/gated.php' => config_path('gated.php'),
            __DIR__.'/../resources/js' => public_path('/vendor/state/gated/js'),
            __DIR__.'/../resources/css' => public_path('/vendor/state/gated/css'),
            __DIR__.'/../resources/blueprints' => resource_path('blueprints/collections'),
            __DIR__.'/../resources/content' => base_path('content/collections'),
        ], 'gated');
    }

    protected function createRouteBinding(): void
    {
        Route::bind('gate', function ($handle) {
            $config = config('gated.gates.'.$handle);

            return Gate::create($handle, $config);
        });
    }

    protected function registerProtector(): void
    {
        app(ProtectorManager::class)->extend('gated', function ($app) {
            return new GateProtector;
        });
    }

}
