<?php

namespace MateuszPeczkowski\NovaHeartbeatResourceField;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use MateuszPeczkowski\NovaHeartbeatResourceField\Commands\ClearExpiredHeartbeats;
use Outl1ne\NovaTranslationsLoader\LoadsNovaTranslations;

class HeartbeatResourceServiceProvider extends ServiceProvider
{
    use LoadsNovaTranslations;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Load translations
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../migrations' => database_path('migrations'),
        ], 'migrations');

        // Config
        $this->publishes([
            __DIR__ . '/../config/nova-heartbeat-resource-field.php' => config_path('nova-heartbeat-resource-field.php'),
        ], 'config');

        // Load translations
        $this->loadTranslations(__DIR__ . '/../lang', 'nova-heartbeat-resource');

        // Merge config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/nova-heartbeat-resource-field.php',
            'nova-heartbeat-resource-field'
        );

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-heartbeat-resource-field', __DIR__.'/../dist/js/heartbeat.js');
            Nova::style('nova-heartbeat-resource-field', __DIR__.'/../dist/css/heartbeat.css');
        });

        // Load routes
        $this->app->booted(function () {
            $this->routes();

            $this->commands([
                ClearExpiredHeartbeats::class,
            ]);
        });
    }

    /**
     * Register the field's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached())
            return;

        Route::middleware(['nova'])
            ->prefix('nova-vendor/nova-heartbeat-resource-field')
            ->namespace('\MateuszPeczkowski\NovaHeartbeatResourceField\Http\Controllers')
            ->group(__DIR__ . '/../routes/api.php');
    }

    public static function getTableName()
    {
        return config('nova-heartbeat-resource-field.table_name', 'nova_heartbeats');
    }

    public static function getHeartbeatsModel()
    {
        return config('nova-heartbeat-resource-field.heartbeat_model', \MateuszPeczkowski\NovaHeartbeatResourceField\Models\HeartbeatResource::class);
    }
}
