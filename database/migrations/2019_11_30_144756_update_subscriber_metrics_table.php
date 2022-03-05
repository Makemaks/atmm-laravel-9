<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSubscriberMetricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriber_metrics', function (Blueprint $table) {
            $table->dropColumn('time_streamed');
        });

        Schema::table('subscriber_metrics', function (Blueprint $table) {
            $table->timestamp('time_streamed')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriber_metrics', function (Blueprint $table) {
            $table->dropColumn('time_streamed');
        });

        Schema::table('subscriber_metrics', function (Blueprint $table) {
            $table->dateTime('time_streamed')->nullable();
        });
    }
}
