<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeviceNameInAppactivitylogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('appactivitylogs', function (Blueprint $table) {
        $table->string('device_name', 100)->after('device_os')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('appactivitylogs', function (Blueprint $table) {
         $table->dropColumn('device_name');
      });
    }
}
