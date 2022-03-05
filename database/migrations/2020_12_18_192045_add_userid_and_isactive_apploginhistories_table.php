<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUseridAndIsactiveApploginhistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('apploginhistories', function (Blueprint $table) {
            $table->integer('user_id')->nullable()->comment('user who logged in')->after('device_fullinfo');
            $table->boolean('is_active')->nullable(false)->default(0)->after('user_id');
            $table->timestamp('last_active', $precision = 0)->nullable()->comment('time last active updated every 3 to 5minutes')->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('apploginhistories', function (Blueprint $table) {
           $table->dropColumn('user_id');
           $table->dropColumn('is_active');
           $table->dropColumn('last_active');
        });
    }
}
