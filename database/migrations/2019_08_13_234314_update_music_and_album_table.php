<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMusicAndAlbumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('music_details', function (Blueprint $table) {
            $table->dropForeign('music_details_band_album_id_foreign');
            $table->dropColumn('band_album_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('music_details', function (Blueprint $table) {
            $table->string('band_album_id')->nullable();
        });
    }
}
