<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAppactivitylogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('appactivitylogs', function (Blueprint $table) {
          $table->string('device_version')->nullable()->change();
          $table->string('device_os')->nullable()->change();
          $table->string('device_model')->nullable()->change();
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
        $table->string('device_version')->nullable()->change();
        $table->string('device_os')->nullable()->change();
        $table->string('device_model')->nullable()->change();
      });
    }
}
