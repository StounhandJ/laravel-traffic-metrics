<?php


return [

    /*
     | Your kafka brokers url.
     */
    'kafka_brokers' => env('KAFKA_BROKERS', 'localhost:9092'),

    'models' => [
        'metrics' => StounhandJ\LaravelTrafficMetrics\Models\Metrics::class,

        'metrics_table_name' => "metrics",

        'metrics_column_uri_size' => 255,
    ]


];