<?php

namespace StounhandJ\LaravelTrafficMetrics;

use Illuminate\Support\ServiceProvider;

class TrafficMetricsProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        if (!app()->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/../config/trafficMetrics.php', 'trafficMetrics');
        }
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        if (app()->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../config/trafficMetrics.php' => config_path('trafficMetrics.php'),
            ], 'traffic-metrics');
        }
    }
}