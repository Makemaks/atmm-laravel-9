<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConvertedFieldsInVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('video_details', function (Blueprint $table) {
             $table->string('video_480',200)->after('uploaded_via')->nullable();
             $table->string('video_720',200)->after('video_480')->nullable();
             $table->string('video_1080',200)->after('video_720')->nullable();
             $table->integer('duration')->after('video_1080')->nullable();
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
            $table->dropColumn('video_480');
            $table->dropColumn('video_720');
            $table->dropColumn('video_1080');
            $table->dropColumn('duration');
        });
    }
}
