<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMusicDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('music_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedinteger('band_album_id');
            $table->string('title');
            $table->date('date_release');
            $table->string('audio')->nullable();
            $table->timestamps();

            $table->foreign('band_album_id')->references('id')->on('band_albums')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('music_details');
    }
}
