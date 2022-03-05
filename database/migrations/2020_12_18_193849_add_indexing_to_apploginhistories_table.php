<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexingToApploginhistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('apploginhistories', function (Blueprint $table) {
          $table->index('user_id', 'user_id_index');
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
         $table->dropIndex('user_id_index');
      });
    }
}
