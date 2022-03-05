<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImageToVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('video_details', function (Blueprint $table) {
            $table->string('image')->after('video')->nullable();
            $table->string('image_width')->after('image')->nullable();
            $table->string('image_height')->after('image_width')->nullable();
            $table->string('thumbnail')->after('image_height')->nullable();
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
        Schema::table('video_details', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->dropColumn('image_width');
            $table->dropColumn('image_height');
            $table->dropColumn('thumbnail');
            $table->dropColumn('thumbnail_width');
            $table->dropColumn('thumbnail_height');
        });
    }
}
