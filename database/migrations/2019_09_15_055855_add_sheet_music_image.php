<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSheetMusicImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sheet_musics', function (Blueprint $table) {
            $table->string('image')->after('file')->nullable();
            $table->string('image_width')->after('image')->nullable();
            $table->string('image_height')->after('image_width')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sheet_musics', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->dropColumn('image_width');
            $table->dropColumn('image_height');
        });
    }
}
