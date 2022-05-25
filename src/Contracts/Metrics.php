<?php

namespace StounhandJ\LaravelTrafficMetrics\Contracts;

use Illuminate\Support\Carbon;

interface Metrics
{
    public static function findById(int $id): \Illuminate\Database\Eloquent\Builder|self;

    public static function findByUri(string $uri): \Illuminate\Database\Eloquent\Builder|self;

    public static function create(string $uri): \Illuminate\Database\Eloquent\Builder|self;

    public function addViews(int $views = 1): self;

    public function getViews(): int;

    public function setLastViews(Carbon $carbon): self;

    public function save();
}