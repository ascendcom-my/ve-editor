<?php

namespace Bigmom\VeEditor\Providers;

use Bigmom\VeEditor\Commands\PullVeEditor;
use Bigmom\VeEditor\Facades\Asset;
use Bigmom\VeEditor\Managers\AssetManager;
use Bigmom\VeEditor\View\Components\Layout;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class VeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        config([
            'auth.guards.ve-editor' => array_merge([
                'driver' => config('ve.guard.driver', 'session'),
                'provider' => config('ve.guard.provider', 'users'),
            ], config('auth.guards.ve-editor', [])),
        ]);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/ve.php' => config_path('ve.php'),
        ]);

        $this->loadRoutesFrom(__DIR__.'/../routes.php');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'veeditor');

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/ve'),
        ], 'public');

        if ($this->app->runningInConsole()) {
            $this->commands([
                PullVeEditor::class,
            ]);
        }

        $this->app->singleton('asset', function ($app) {
            return new AssetManager;
        });

        $this->app->singleton('sasset', function () {
            return function ($path) {
                $url = Asset::get($path);

                if (!$url) throw new \Exception('Asset Not Found');
                return $url;
            };
        });

        Blade::directive('asset', function ($path) {
            $path = substr($path, 1, -1);
            $url = app('sasset')($path);
            return e($url);
        });
        
        Blade::directive('svg', function ($path) {
            $path = substr($path, 1, -1);
            $svg = file_get_contents(app('sasset')($path));
            $svg = preg_replace('/\<\?xml(.*)\?\>/', '', $svg);
            $svg = preg_replace('/\<\!\-\-(.*)--\>/', '', $svg);
            return $svg;
        });

        $this->gate();
    }

    /**
     * Register the VE Editor gate.
     *
     * This gate determines who can access VE Editor in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('accessVeEditor', function ($user = null) {
            return in_array(optional($user)->email, config('ve.allowed-users'));
        });
    }
}
