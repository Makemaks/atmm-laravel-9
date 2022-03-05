<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTrackSequenceInAlbumSongs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('music_details_band_albums', function (Blueprint $table) {
            $table->integer('track_sequence');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('music_details_band_albums', function (Blueprint $table) {
            $table->dropColumn('track_sequence');
        });
    }
}
