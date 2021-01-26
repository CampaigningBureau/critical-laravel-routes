<?php

namespace CampaigningBureau\CriticalLaravelRoutes;

use Illuminate\Support\ServiceProvider;
use CampaigningBureau\CriticalLaravelRoutes\Commands\GenerateUrls;

class CriticalLaravelRoutesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // publish config
        $this->publishes([
            __DIR__ . '/config/critical-laravel-routes.php' => config_path('critical-laravel-routes.php'),
        ], 'config');
        
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateUrls::class,
            ]);
        }

    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/critical-laravel-routes.php', 'critical-laravel-routes');

        $this->app->singleton('critical-laravel-routes', function($app) {
            return new Manager();
        });
    }
}