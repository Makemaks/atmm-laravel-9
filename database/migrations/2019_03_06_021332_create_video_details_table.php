<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('video_category_id');
            $table->unsignedinteger('band_album_id');
            $table->string('title');
            $table->date('date_release');
            $table->string('video')->nullable();
            $table->timestamps();

            $table->foreign('video_category_id')->references('id')->on('video_categories')->onDelete('cascade');
            $table->foreign('band_album_id')->references('id')->on('band_albums')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('video_details');
    }
}
