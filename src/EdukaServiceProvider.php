<?php

namespace Nidavellir\Cube;

use Illuminate\Support\ServiceProvider;

class NidavellirCubeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->importMigrations();
        $this->registerObservers();
        $this->registerPolicies();
        $this->registerCommands();
        $this->publishResources();
        $this->loadTestRoutes();
        $this->loadRoutes();
        $this->loadViews();
    }

    protected function loadViews()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'site');
    }

    protected function loadRoutes()
    {
        /*
        Route::middleware(['web'])
             ->group(function () {
                 include __DIR__.'/../routes/web.php';
             });
        */
    }

    protected function loadTestRoutes()
    {
        /*
        if (app()->environment() != 'production') {
            if (file_exists(__DIR__.'/../routes/tests.php')) {
                $routesPath = __DIR__.'/../routes/tests.php';
                Route::middleware(['web'])
                 ->group(function () use ($routesPath) {
                     include $routesPath;
                 });
            }
        }
        */
    }

    public function register()
    {
        //
    }

    protected function importMigrations(): void
    {
        //$this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    protected function registerObservers(): void
    {
        //User::observe(UserObserver::class);
    }

    protected function registerPolicies(): void
    {
        //Gate::policy(User::class, UserPolicy::class);
    }

    protected function registerCommands(): void
    {
        /*
        $this->commands([
            TestCommand::class,
        ]);
        */
    }

    protected function publishResources()
    {
        /*
        $this->publishes([
            __DIR__.'/../resources/overrides/' => base_path('/'),
        ], 'package-name');
        */
    }
}
