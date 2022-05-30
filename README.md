HtmlDom-Laravel
=======
<p align="center">
<a href="https://packagist.org/packages/stounhandj/laravel-traffic-metrics"><img src="https://img.shields.io/packagist/dt/stounhandj/laravel-traffic-metrics" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/stounhandj/laravel-traffic-metrics"><img src="https://img.shields.io/packagist/v/stounhandj/laravel-traffic-metrics" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/stounhandj/laravel-traffic-metrics"><img src="https://img.shields.io/packagist/l/stounhandj/laravel-traffic-metrics" alt="License"></a>
</p>

### A package for Laravel 6, 7, 8 to metrics unique page views using kafka

## Installation

```
$ composer require stounhandj/laravel-traffic-metrics
```
Or
```json
{
    "require": {
        "stounhandj/laravel-traffic-metrics": "^0.1.0"
    }
}
```

## Setup

1. Use following:
```shell
php artisan vendor:publish --tag=traffic-metrics
php artisan vendor:publish --tag=laravel-kafka-config
```
According to the standard, a unique visit is considered to be a user's visit to the page once every 1 minute. This can be changed in the configuration.
2. Run migrations
```shell
php artisan migrate
```
3. Set up a connection to kafka:
In config/kafka.php setup brokers. More information [here](https://junges.dev/documentation/laravel-kafka/v1.7/3-installation-and-setup)
4. Add provider in config/app.php:
```php
StounhandJ\LaravelTrafficMetrics\TrafficMetricsProvider::class,
```
4. Add Middleware in Kernel.php:
```php
'metrics' => \StounhandJ\LaravelTrafficMetrics\Middleware\MetricsMiddleware::class,
```

## Usage

1. Use Middleware:
```php
Route::get('/p/{product:id}', [ProductController::class, "index"])
    ->middleware("metrics")
    ->name("product.details");
```

2. Put the handler on permanent execution:
```shell
php artisan metrics:check
```

3. Getting metrics
```php
Metrics::findByUri("/welcome")->getViews() 
```