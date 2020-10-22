<?php

namespace Bigmom\VeEditor\Providers;

use Bigmom\VeEditor\Commands\PullVeEditor;
use Bigmom\VeEditor\Managers\AssetManager;
use Bigmom\VeEditor\Facades\Asset;
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
        //
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

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views'),
        ]);
        
        $this->loadViewComponentsAs('ve', [
            Layout::class,
        ]);

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
                $nameAndFile = explode('.', $path);
                $name = $nameAndFile[0] ?? false;
                if (!$name) throw new \Exception('No folder specified for asset');
                $file = $nameAndFile[1] ?? false;
                if (!$file) throw new \Exception('No file specified for asset');

                $url = Asset::get($nameAndFile);

                if (!$file || !$url) throw new \Exception('Asset Not Found');
                return $url;
            };
        });

        Blade::directive('asset', function ($path) {
            $path = substr($path, 1, -1);
            $url = app('sasset')($path);
            return e($url);
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
