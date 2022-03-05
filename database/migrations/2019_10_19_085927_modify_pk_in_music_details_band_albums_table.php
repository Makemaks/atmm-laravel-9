<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyPkInMusicDetailsBandAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('music_details_band_albums', function(Blueprint $table) {
            $table->renameColumn('id', 'album_music_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('music_details_band_albums', function(Blueprint $table) {
            $table->renameColumn('album_music_id', 'id');
        });
    }
}
