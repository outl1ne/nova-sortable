<?php

namespace Outl1ne\NovaSortable;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Outl1ne\NovaTranslationsLoader\LoadsNovaTranslations;

class ToolServiceProvider extends ServiceProvider
{
    use LoadsNovaTranslations;

    public function boot()
    {
        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-sortable', __DIR__.'/../dist/js/entry.js');
            Nova::style('nova-sortable', __DIR__.'/../dist/css/tool.css');

            Nova::provideToScript([
                'nova-sortable' => config('nova-sortable'),
            ]);

        });

        if ($this->app->runningInConsole()) {
            // Publish config
            $this->publishes([
                __DIR__.'/../config/' => config_path(),
            ], 'config');
        }

        $this->loadTranslations(__DIR__.'/../resources/lang', 'nova-sortable', true);
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova'])
            ->prefix('nova-vendor/nova-sortable')
            ->domain(config('nova.domain', null))
            ->namespace('\Outl1ne\NovaSortable\Http\Controllers')
            ->group(__DIR__.'/../routes/api.php');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/nova-sortable.php', 'nova-sortable',
        );
    }
}
