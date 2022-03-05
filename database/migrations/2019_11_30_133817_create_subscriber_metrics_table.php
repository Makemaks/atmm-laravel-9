<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriberMetricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriber_metrics', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('song_id')->nullable();
            //$table->unsignedInteger('user_id')->nullable();
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->string('ip_adress',50)->nullable();
            $table->dateTime('time_streamed')->nullable();
            $table->timestamps();
            $table->foreign('song_id')->references('id')->on('music_details');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriber_metrics');
    }
}
