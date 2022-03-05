<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagefieldsPodcastsAudiobooks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('podcasts', function (Blueprint $table) {
          $table->string('image')->nullable();
          $table->string('image_width')->nullable();
          $table->string('image_height')->nullable();
          $table->string('thumbnail')->nullable();
          $table->string('thumbnail_width')->nullable();
          $table->string('thumbnail_height')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('podcasts', function (Blueprint $table) {
          $table->dropColumn('image');
          $table->dropColumn('image_width');
          $table->dropColumn('image_height');
          $table->dropColumn('thumbnail');
          $table->dropColumn('thumbnail_width');
          $table->dropColumn('thumbnail_height');
      });
    }
}
