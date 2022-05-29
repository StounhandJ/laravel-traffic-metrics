<?php

namespace StounhandJ\LaravelTrafficMetrics\Command;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Junges\Kafka\Contracts\KafkaConsumerMessage;
use Junges\Kafka\Facades\Kafka;
use StounhandJ\LaravelTrafficMetrics\Contracts\Metrics as MetricsContract;

class CheckViewsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'metrics:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check page visits and saves';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $consumer = Kafka::createConsumer([config('trafficMetrics.topic')], config('trafficMetrics.consumer_group_id'))
            ->withHandler(function (KafkaConsumerMessage $message) {
                global $above;
                if ($above == null)
                    $above = [];

                $ip = $message->getBody()["ip"];
                $uri = $message->getBody()["uri"];

                if (!array_key_exists($uri, $above))
                    $above[$uri] = [];

                if (!array_key_exists($ip, $above[$uri])
                    || $message->getTimestamp() - $above[$uri][$ip] > config('trafficMetrics.milliseconds')
                ) {
                    var_dump($above);
                    if (array_key_exists($ip, $above[$uri])) {
                        $this->info(Carbon::now()->timestamp);
                        $this->info($above[$uri][$ip]);
                        $this->info(Carbon::now()->timestamp - $above[$uri][$ip]);
                    }
                    $above[$uri][$ip] = $message->getTimestamp();
                    $metrics = app(MetricsContract::class)->findByUri($uri);
                    if (!$metrics->exists) {
                        $metrics = app(MetricsContract::class)::create($uri);
                    }

                    $metrics->addViews();

                    $this->info(sprintf("%s visit %s", $ip, $uri));
                }
            })
            ->build();

        $consumer->consume();

        $this->info("Bye ;<");
        return 0;
    }
}