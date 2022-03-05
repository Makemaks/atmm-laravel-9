<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsPublicInFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('band_albums', function (Blueprint $table) {
            $table->boolean('is_public')->default(0);
        });
        Schema::table('instrumentals', function (Blueprint $table) {
            $table->boolean('is_public')->default(0);
        });
        Schema::table('music_details', function (Blueprint $table) {
            $table->boolean('is_public')->default(0);
        });
        Schema::table('podcasts', function (Blueprint $table) {
            $table->boolean('is_public')->default(0);
        });
        Schema::table('sheet_musics', function (Blueprint $table) {
            $table->boolean('is_public')->default(0);
        });
        Schema::table('video_details', function (Blueprint $table) {
            $table->boolean('is_public')->default(0);
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
            $table->dropColumn('is_public');
        });
        Schema::table('instrumentals', function (Blueprint $table) {
            $table->dropColumn('is_public');
        });
        Schema::table('music_details', function (Blueprint $table) {
            $table->dropColumn('is_public');
        });
        Schema::table('podcasts', function (Blueprint $table) {
            $table->dropColumn('is_public');
        });
        Schema::table('sheet_musics', function (Blueprint $table) {
            $table->dropColumn('is_public');
        });
        Schema::table('video_details', function (Blueprint $table) {
            $table->dropColumn('is_public');
        });
    }
}
