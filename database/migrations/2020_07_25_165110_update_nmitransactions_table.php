<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNmitransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('nmitransactions', function (Blueprint $table) {
          $table->string('order_description',30)->nullable();
          $table->string('cc_number',40)->nullable();
          $table->string('cc_hash',40)->nullable();
          $table->mediumInteger('cc_exp')->nullable();
          $table->string('cc_type',40)->nullable();
          $table->string('processor_id',60)->nullable();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('nmitransactions', function (Blueprint $table) {
          $table->dropColumn('order_description');
          $table->dropColumn('cc_number');
          $table->dropColumn('cc_hash');
          $table->dropColumn('cc_exp');
          $table->dropColumn('cc_type');
          $table->dropColumn('processor_id');
      });
    }
}
