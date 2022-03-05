<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVideoDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('video_details', function (Blueprint $table) {
            $table->dropForeign('video_details_band_album_id_foreign');
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
        /*
        Schema::table('video_details', function (Blueprint $table) {
            $table->foreign('band_album_id')
                ->references('id')
                ->on('band_albums')
                ->onDelete('cascade');
        });
        */
    }
}
