<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateApploginhistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('apploginhistories', function (Blueprint $table) {

           $table->dropColumn('browser_info');
           $table->dropColumn('device');

           $table->string('device_os', 20)->after('ip_address')->nullable();
           $table->integer('device_version')->after('device_os')->nullable();
           $table->string('device_model', 70)->after('device_version')->nullable();
           $table->text('device_fullinfo')->after('device_model')->nullable();

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

          $table->string('browser_info', 200)->after('ip_address')->nullable();
          $table->string('device', 20)->after('browser_info')->nullable();

          $table->dropColumn('device_os');
          $table->dropColumn('device_version');
          $table->dropColumn('device_model');
          $table->dropColumn('device_fullinfo');

        });
    }

}
