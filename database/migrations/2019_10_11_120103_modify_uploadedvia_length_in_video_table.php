<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUploadedviaLengthInVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('video_details', function (Blueprint $table) {
            $table->dropColumn('uploaded_via');
        });

        Schema::table('video_details', function (Blueprint $table) {
            $table->string('uploaded_via',15)->after('file_size')->nullable();
            //$table->string('uploaded_via',15)->after('file_size')->nullable()->change();
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
            $table->dropColumn('uploaded_via');
        });
    }
}
