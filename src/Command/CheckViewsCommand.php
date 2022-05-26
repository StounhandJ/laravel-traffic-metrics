<?php

namespace StounhandJ\LaravelTrafficMetrics\Command;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Consumers\CallableConsumer;
use Junges\Kafka\Consumers\Consumer;
use Junges\Kafka\Contracts\KafkaConsumerMessage;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;
use mysql_xdevapi\Exception;
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
//        app(MetricsContract::class)->findByUri('other-permission');
        $consumer = Kafka::createConsumer(['topic'], 'check-consumer')
            ->enableBatching()
            ->withBatchSizeLimit(2)
            ->withBatchReleaseInterval(10000)
            ->withHandler(function (Collection $collection) {
                throw new Exception();
            })
            ->build();

        $this->info("Проверка файлов");

        $consumer->consume();
        //Прописать получения данных из очереди и сложения просмотров по ip учитывая интервал для одного пользователя по адресу
        // Интервал указывать в конфиге
        return 0;
    }
}