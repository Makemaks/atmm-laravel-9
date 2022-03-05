<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfusionsoftTrialLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infusionsoft_trial_logs', function (Blueprint $table) {
            $table->increments('id');
            //$table->unsignedInteger('user_id')->nullable();
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->integer('contact_id')->nullable();
            $table->integer('creditcard_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->index('contact_id', 'contact_id_index');
            $table->index('creditcard_id', 'creditcard_id_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infusionsoft_trial_logs');
    }
}
