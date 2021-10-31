<?php

namespace Eduka;

use Eduka\Commands\Install;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class EdukaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->importMigrations();
        $this->registerCommands();
        $this->loadRoutes();
        $this->loadViews();



        //$this->registerObservers();
        //$this->registerPolicies();
        //$this->publishResources();
    }

    protected function registerContainers()
    {
        $this->app->singleton('website-checkout', function () {
            return new WebsiteCheckout();
        });
    }

    protected function loadViews()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'eduka');
    }

    protected function loadRoutes()
    {
        Route::middleware(['web'])
             ->group(function () {

                 include __DIR__.'/../routes/default.php';

                 $envRoutesFile = __DIR__ .
                                  '/../routes/' .
                                  env('APP_ENV') .
                                  '.php';

                 /**
                  * For debug/development purposes eduka will load the
                  * respective environment route file if it exists.
                  */
                if (file_exists($envRoutesFile)) {
                    include $envRoutesFile;
                }
             });
    }

    public function register()
    {
        $this->registerContainers();
        $this->registerMacros();
    }

    protected function importMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
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
        $this->commands([
            Install::class,
        ]);
    }

    protected function publishResources()
    {
        $this->publishes([
        __DIR__.'/../resources/overrides/' => base_path('/'),
        ], 'eduka');
    }

    protected function registerMacros()
    {
        // Include all files from the Macros folder.
        Collection::make(glob(__DIR__ . '/../Macros/*.php'))
                  ->mapWithKeys(function ($path) {
                      return [$path => pathinfo($path, PATHINFO_FILENAME)];
                  })
                  ->each(function ($macro, $path) {
                      require_once $path;
                  });
    }
}
