<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expos', function (Blueprint $table) {
            $table->increments('id');
            //$table->unsignedInteger('user_id')->nullable();
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->string('value');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->unique(['value']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expos');
    }
}
