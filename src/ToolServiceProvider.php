<?php

namespace OptimistDigital\NovaSortable;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->translations();

        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-sortable', __DIR__ . '/../dist/js/tool.js');
            Nova::style('nova-sortable', __DIR__ . '/../dist/css/tool.css');
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) return;

        Route::middleware(['nova'])
            ->prefix('nova-vendor/nova-sortable')
            ->namespace('\OptimistDigital\NovaSortable\Http\Controllers')
            ->group(__DIR__ . '/../routes/api.php');
    }

    protected function translations()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../resources/lang' => resource_path('lang/vendor/nova-sortable')], 'translations');
        } else if (method_exists('Nova', 'translations')) {
            // Load local translation files
            $localTranslationFiles = File::files(__DIR__ . '/../resources/lang');
            foreach ($localTranslationFiles as $file) {
                Nova::translations($file->getPathName());
            }

            // Load project translation files
            $projectTransFilesPath = resource_path('lang/vendor/nova-sortable');
            if (File::exists($projectTransFilesPath)) {
                $projectTranslationFiles = File::files($projectTransFilesPath);
                foreach ($projectTranslationFiles as $file) {
                    Nova::translations($file->getPathName());
                }
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
