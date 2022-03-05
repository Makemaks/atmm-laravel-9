<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSongsAuthors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('songs_authors', function (Blueprint $table) {
            $table->integer('music_detail_id')->unsigned();
            $table->foreign('music_detail_id')
                ->references('id')
                ->on('music_details')
                ->onDelete('cascade');
            $table->integer('author_id')->unsigned();
            $table->foreign('author_id')
                ->references('id')
                ->on('authors')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('songs_authors');
    }
}
