<?php

namespace NormanHuth\NovaPerspectives;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as Provider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use NormanHuth\NovaBasePackage\ServiceProviderTrait;

class ServiceProvider extends Provider
{
    use ServiceProviderTrait;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->addAbout();

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-perspectives', __DIR__ . '/../dist/js/perspektive.js');
        });

        $this->app->booted(function () {
            $this->routes();
        });

        $this->publishes([
            __DIR__ . '/../config/nova-perspectives.php' => config_path('nova-perspectives.php'),
        ], 'nova-perspectives-config');

        if ($this->app->runningInConsole()) {
            $this->commands($this->getCommands());
        }
    }

    /**
     * Get all package commands.
     *
     * @return array
     */
    protected function getCommands(): array
    {
        return array_filter(array_map(function ($item) {
            return '\\' . __NAMESPACE__ . '\\Console\\Commands\\' . pathinfo($item, PATHINFO_FILENAME);
        }, glob(__DIR__ . '/Console/Commands/*.php')), function ($item) {
            return class_basename($item) != 'Command';
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova'])
            ->prefix('nova-vendor/nova-perspectives')
            ->group(__DIR__ . '/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/nova-perspectives.php',
            'nova-perspectives'
        );
    }
}
