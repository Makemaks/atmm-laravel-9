<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstrumentalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instrumentals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('music_detail_id');
            $table->string('high_key_video')->nullable();
            $table->string('low_key_video')->nullable();
            $table->string('high_key_audio')->nullable();
            $table->string('low_key_audio')->nullable();
            $table->timestamps();

            $table->foreign('music_detail_id')->references('id')->on('music_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instrumentals');
    }
}
