<?php

namespace Eduka;

use Eduka\Commands\Install;
use Eduka\Facades\CourseCheckout;
use Eduka\Models\Affiliate;
use Eduka\Models\Chapter;
use Eduka\Models\Course;
use Eduka\Models\Link;
use Eduka\Models\User;
use Eduka\Models\Video;
use Eduka\Observers\ChapterObserver;
use Eduka\Observers\CourseObserver;
use Eduka\Observers\LinkObserver;
use Eduka\Observers\UserObserver;
use Eduka\Observers\VideoObserver;
use Eduka\Policies\ChapterPolicy;
use Eduka\Policies\CoursePolicy;
use Eduka\Policies\LinkPolicy;
use Eduka\Policies\UserPolicy;
use Eduka\Policies\VideoPolicy;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class EdukaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->updateSchemaStringLength();
        $this->importMigrations();
        $this->registerCommands();
        $this->loadRoutes();
        $this->loadViews();
        $this->registerGates();
        $this->registerReferer();
        $this->registerBladeDirectives();
        $this->registerObservers();
        $this->publishResources();
        $this->registerBladeComponents();
    }

    protected function registerContainers()
    {
        $this->app->singleton('course-checkout', function () {
            return new CourseCheckout();
        });
    }

    protected function registerBladeComponents()
    {
        // Register blade components namespace.
        Blade::componentNamespace('Eduka\\Views\\Components', 'eduka');
    }

    protected function loadViews()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'eduka');
    }

    protected function loadRoutes()
    {
        Route::middleware(['web'])
             ->group(function () {

                //common routes file.
                 include __DIR__.'/../routes/default.php';

                 /**
                  * For debug/development purposes eduka will load the
                  * respective environment route file if it exists.
                  */
                 $envRoutesFile = __DIR__.
                              '/../routes/'.
                              env('APP_ENV').
                              '.php';
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

    protected function registerObservers()
    {
        User::observe(UserObserver::class);
        Video::observe(VideoObserver::class);
        Chapter::observe(ChapterObserver::class);
        Link::observe(LinkObserver::class);
        Course::observe(CourseObserver::class);
    }

    protected function registerPolicies(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Chapter::class, ChapterPolicy::class);
        Gate::policy(Link::class, LinkPolicy::class);
        Gate::policy(Video::class, VideoPolicy::class);
        Gate::policy(Course::class, CoursePolicy::class);
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
            __DIR__.'/../overrides/' => base_path('/'),
        ], 'eduka-overrides');
    }

    protected function registerMacros()
    {
        // Include all files from the Macros folder.
        Collection::make(glob(__DIR__.'/../Macros/*.php'))
                  ->mapWithKeys(function ($path) {
                      return [$path => pathinfo($path, PATHINFO_FILENAME)];
                  })
                  ->each(function ($macro, $path) {
                      require_once $path;
                  });
    }

    protected function registerGates()
    {
        /*
         * If the video is not active, return false.
         * If the user is logged in, return true.
         * If the video is free, return true.
         */
        Gate::define('play-video', function (?User $user, Video $video) {
            if ($video->is_active == false) {
                return false;
            }

            if (Auth::id()) {
                return true;
            }

            return $video->is_free;
        });
    }

    protected function registerReferer()
    {
        if (request()->has('ref')) {
            $name = request()->input('ref');
            $affiliate = Affiliate::firstWhere('name', $name);

            if ($affiliate) {
                session(['referer' => $affiliate->domain]);
            }
        } elseif (request()->headers->get('referer') != null) {
            $domain = request()->headers->get('referer');
            $affiliate = Affiliate::firstWhere('domain', $this->baseDomain($domain));

            if ($affiliate) {
                session(['referer' => $affiliate->domain]);
            }
        }

        // Testing purposes in a non-production environment.
        if (env('REFERER') && app()->environment() != 'production') {
            session(['referer' => $this->baseDomain(env('REFERER'))]);
        }
    }

    protected function updateSchemaStringLength()
    {
        Schema::defaultStringLength(191);
    }

    protected function registerBladeDirectives()
    {
        Blade::if('env', function ($environment) {
            return app()->environment($environment);
        });

        Blade::if('routename', function ($name) {
            return Route::currentRouteName() == $name;
        });

        // Register checkout return url.
        Route::any(
            'paddle/thanks/{checkout}',
            '\MasteringNova\Features\Purchased\Controllers\PurchasedController@thanks'
        )
             ->name('purchased.thanks');
    }

    public function addScheme($url, $scheme = 'http://')
    {
        return parse_url($url, PHP_URL_SCHEME) === null ? $scheme.$url : $url;
    }

    public function baseDomain($url)
    {
        $url = $this->addScheme($url);

        $urlData = parse_url($url);
        $urlHost = isset($urlData['host']) ? $urlData['host'] : '';
        $isIP = (bool) ip2long($urlHost);
        if ($isIP) { /* To check if it's ip then return same ip */
            return $urlHost;
        }
        /** Add/Edit you TLDs here */
        $urlMap = ['io', 'dev', 'com', 'uk', 'pt', 'org', 'net'];

        $host = '';
        $hostData = explode('.', $urlHost);
        if (isset($hostData[1])) { /** To check "localhost" because it'll be without any TLDs */
            $hostData = array_reverse($hostData);

            if (array_search($hostData[1].'.'.$hostData[0], $urlMap) !== false) {
                $host = $hostData[2].'.'.$hostData[1].'.'.$hostData[0];
            } elseif (array_search($hostData[0], $urlMap) !== false) {
                $host = $hostData[1].'.'.$hostData[0];
            }

            return $host;
        }

        return (isset($hostData[0]) && $hostData[0] != '') ? $hostData[0] : 'error no domain'; /* You can change this error in future */
    }
}
