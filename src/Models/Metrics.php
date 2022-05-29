<?php

namespace StounhandJ\LaravelTrafficMetrics\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use StounhandJ\LaravelTrafficMetrics\Contracts\Metrics as MetricsContract;

class Metrics extends Model implements MetricsContract
{
    use HasFactory;

    const CREATED_AT = 'first_view';
    const UPDATED_AT = 'last_view';

    public $timestamps = true;

    protected $fillable = ["uri", "views", "last_view"];

    public function getTable()
    {
        return config('trafficMetrics.models.metrics_table_name', parent::getTable());
    }

    public static function findById(int $id): \Illuminate\Database\Eloquent\Builder|Metrics
    {
        return static::query()->where('id', $id)->first();
    }

    public static function findByUri(string $uri): \Illuminate\Database\Eloquent\Builder|Metrics
    {
        return static::query()->where('uri', $uri)->first() ?? new Metrics();
    }

    public static function create(string $uri): \Illuminate\Database\Eloquent\Builder|Metrics
    {
        return static::query()->create([
            "uri" => $uri,
            "views" => 0,
            "last_view" => Carbon::now()
        ]);
    }

    public function addViews(int $views = 1): MetricsContract
    {
        $this->increment("views", $views);
        return $this;
    }

    public function getViews(): int
    {
        return $this->views;
    }

    public function setLastViews(Carbon $carbon): MetricsContract
    {
        $this->last_view = $carbon;
        return $this;
    }
}