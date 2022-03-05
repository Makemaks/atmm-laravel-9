<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSheetMusicThumbnailField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sheet_musics', function (Blueprint $table) {
            $table->string('thumbnail')->after('file')->nullable();
            $table->string('thumbnail_width')->after('thumbnail')->nullable();
            $table->string('thumbnail_height')->after('thumbnail_width')->nullable();
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
            $table->dropColumn('thumbnail');
            $table->dropColumn('thumbnail_width');
            $table->dropColumn('thumbnail_height');
        });
    }
}
