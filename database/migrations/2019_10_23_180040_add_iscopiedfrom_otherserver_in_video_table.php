<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIscopiedfromOtherserverInVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('video_details', function (Blueprint $table) {
            $table->boolean('iscopiedinsecondserver')
                  ->after('video_1080')
                  ->nullable(false)
                  ->default(0);
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
            $table->dropColumn('iscopiedinsecondserver');
       });
    }
}
