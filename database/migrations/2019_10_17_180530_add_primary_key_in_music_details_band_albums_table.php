<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrimaryKeyInMusicDetailsBandAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('music_details_band_albums', function (Blueprint $table) {
            $table->increments('id')->first();
            $table->timestamps();
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
            $table->dropColumn('id');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }



}
