<?php

namespace StounhandJ\LaravelTrafficMetrics\Middleware;

use Closure;
use Illuminate\Http\Request;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

class MetricsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $message = new Message(
            body: [
                'ip' => $request->ip(),
                'uri' => $request->url()
            ]
        );

        $producer = Kafka::publishOn('topic', config('trafficMetrics.kafka_brokers'))->withMessage($message);
        $producer->send();
        return $next($request);
    }
}