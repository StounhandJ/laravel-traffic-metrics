<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $metricsTableName = config('trafficMetrics.models.metrics_table_name');
        $columnUriSize = config('trafficMetrics.models.metrics_column_uri_size');

        Schema::create($metricsTableName, function (Blueprint $table) use ($columnUriSize) {
            $table->id();
            $table->string("uri", $columnUriSize);
            $table->integer("views");
            $table->timestamp('last_view')->nullable();
            $table->timestamp('first_view')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $metricsTableName = config('trafficMetrics.models.metrics_table_name');
        Schema::dropIfExists($metricsTableName);
    }
};