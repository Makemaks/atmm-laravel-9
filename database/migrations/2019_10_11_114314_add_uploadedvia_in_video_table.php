<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUploadedviaInVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('video_details', function (Blueprint $table) {
             $table->string('uploaded_via')->after('file_size')->nullable()->comment = "default , cron";
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
            //$table->dropColumn('uploaded_via');
        });

    }
}
