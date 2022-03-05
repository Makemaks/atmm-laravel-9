<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SheetMusicExploreAccess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sheet_musics', function (Blueprint $table) {
            $table->boolean('show_in_explore')
                  ->after('is_public')
                  ->nullable(false)
                  ->default(0);
       });
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sheet_musics', function (Blueprint $table) {
            $table->dropColumn('show_in_explore');
       });
    }
}
