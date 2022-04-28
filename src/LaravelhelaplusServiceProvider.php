<?php

namespace Helaplus\Laravelhelaplus;

use Illuminate\Support\ServiceProvider;

class LaravelhelaplusServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'helaplus');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'helaplus');
         $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
         $this->loadRoutesFrom(__DIR__ . '/../routes/web.php'); 

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravelhelaplus.php', 'laravelhelaplus');

        // Register the service the package provides.
        $this->app->singleton('laravelhelaplus', function ($app) {
            return new Laravelhelaplus;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['laravelhelaplus'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/laravelhelaplus.php' => config_path('laravelhelaplus.php'),
        ], 'laravelhelaplus.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/helaplus'),
        ], 'laravelhelaplus.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/helaplus'),
        ], 'laravelhelaplus.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/helaplus'),
        ], 'laravelhelaplus.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
