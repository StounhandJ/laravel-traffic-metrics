<?php

namespace StounhandJ\LaravelTrafficMetrics;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use StounhandJ\LaravelTrafficMetrics\Contracts\Metrics as MetricsContract;

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

            $this->app->bind(MetricsContract::class, config('trafficMetrics.models.metrics'));

            $this->publishes([
                __DIR__ . '/../migrations/create_metrics.php' => $this->getMigrationFileName('create_metrics.php'),
            ], 'traffic-metrics');
        }
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @return string
     */
    protected function getMigrationFileName($migrationFileName): string
    {
        $timestamp = date('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make($this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $migrationFileName) {
                return $filesystem->glob($path . '*_' . $migrationFileName);
            })
            ->push($this->app->databasePath() . "/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }
}