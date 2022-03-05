<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveIdTokenApploginhistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('apploginhistories', function (Blueprint $table) {
           $table->dropColumn('idToken');
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
          $table->text('idToken')->after('last_active')->nullable();
      });
    }
}
