<?php

namespace Bigmom\VeEditor\Providers;

use Bigmom\VeEditor\Commands\PullVeEditor;
use Bigmom\VeEditor\Facades\Asset;
use Bigmom\VeEditor\Managers\AssetManager;
use Bigmom\VeEditor\Managers\SceneManager;
use Bigmom\VeEditor\Services\Validate as ValidateService;
use Bigmom\VeEditor\View\Components\Layout;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
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
        $routes = [];
        if (config('ve.main')) {
            $routes = [
                [
                    'title' => 'Scene',
                    'name' => 've-editor.scene.getIndex',
                    'permission' => 've-editor-manage',
                ],
                [
                    'title' => 'Static Assets',
                    'name' => 've-editor.staticAsset',
                    'permission' => 've-editor-manage'
                ],
                [
                    'title' => 'Content Asset',
                    'name' => 've-editor.contentAsset',
                    'permission' => 've-editor-manage'
                ],
                [
                    'title' => 'Downloadable',
                    'name' => 've-editor.downloadable',
                    'permission' => 've-editor-manage'
                ],
            ];
        }

        config([
            'bigmom-auth.packages' => array_merge([[
                'name' => 'VE Editor',
                'description' => 'Virtual event editor with a simple asset manager.',
                'routes' => $routes,
                'permissions' => [
                    've-editor-manage',
                ]
            ]], config('bigmom-auth.packages', []))
        ]);

        $this->app->singleton('asset', function ($app) {
            return new AssetManager;
        });

        $this->app->singleton('scene', function ($app) {
            return new SceneManager;
        });

        $this->app->singleton('sasset', function () {
            return function ($path) {
                $url = Asset::get($path);

                if (!$url) throw new \Exception('Asset Not Found: '.$path);
                return $url;
            };
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/ve.php' => config_path('ve.php'),
            ]);

            $this->publishes([
                __DIR__.'/../public' => public_path('vendor/ve'),
            ], 'public');

            $this->commands([
                PullVeEditor::class,
            ]);
        }

        $this->loadRoutesFrom(__DIR__.'/../routes.php');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'veeditor');

        View::composer(
            'veeditor::*', 'Bigmom\VeEditor\View\Composers\SizeComposer'
        );

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
    }
}
