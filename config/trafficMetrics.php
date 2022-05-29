<?php


return [

    /*
     | The name of the topic in the Kafka for storing information about visits
     */
    "topic" => "traffic_metrics",

    /*
     | Kafka's group id for consumer visit updates
     */
    "consumer_group_id" => "consumer_metrics",

    /*
     | The delay between page visits by one user to account for repeated viewing.
     */
    "milliseconds" => env("DELAY_VISIT",60000),

    'models' => [
        'metrics' => StounhandJ\LaravelTrafficMetrics\Models\Metrics::class,

        'metrics_table_name' => "metrics",

        'metrics_column_uri_size' => 255,
    ]


];