<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveAlbumDescriptionAndBandName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('band_albums', function (Blueprint $table) {
            $table->dropColumn('band_name');
            $table->dropColumn('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('band_albums', function (Blueprint $table) {
            $table->string('band_name');
            $table->string('description');
        });
    }
}
