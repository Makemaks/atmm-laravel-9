<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSongsArtists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('songs_artists', function (Blueprint $table) {
            $table->integer('music_detail_id')->unsigned();
            $table->foreign('music_detail_id')
                ->references('id')
                ->on('music_details')
                ->onDelete('cascade');
            $table->integer('artist_id')->unsigned();
            $table->foreign('artist_id')
                ->references('id')
                ->on('artists')
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
        Schema::dropIfExists('songs_artists');
    }
}
