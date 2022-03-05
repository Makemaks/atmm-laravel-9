<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableForMusicDetailsAndBandAlbums extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('music_details_band_albums', function (Blueprint $table) {
            $table->integer('band_album_id')->unsigned();
            $table->foreign('band_album_id')
                ->references('id')
                ->on('band_albums')
                ->onDelete('cascade');
            $table->integer('music_detail_id')->unsigned();
            $table->foreign('music_detail_id')
                ->references('id')
                ->on('music_details')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('music_details_band_albums');
    }
}
